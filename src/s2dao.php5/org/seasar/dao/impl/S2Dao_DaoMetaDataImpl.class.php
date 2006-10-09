<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id: $
//
/**
 * @author nowel
 */
class S2Dao_DaoMetaDataImpl implements S2Dao_DaoMetaData {

    const INSERT_NAMES = '/^(insert|create|add)/i';
    const UPDATE_NAMES = '/^(update|modify|store)/i';
    const DELETE_NAMES = '/^(delete|remove)/i';
    const INSERT_OR_UPDATE_NAMES = '/^(save|saveOrUpdate|insertOrUpdate)/i';
    const DELETE_AFTER_INSERT_NAMES = '/(replace|substitute)/i';
    const startWithSelectPattern = '/^\s*SELECT/i';
    const startWithBeginCommentPattern = '/^\/\*BEGIN\*\/\s*WHERE .+/i';
    const startWithOrderByPattern = '/^(\/\*[^\*]+\*\/)*order by/i';

    protected $daoClass_;
    protected $daoInterface_;
    protected $daoBeanDesc_;
    protected $dataSource_;
    protected $annotationReader_;
    protected $statementFactory_;
    protected $resultSetFactory_;
    protected $resultSetHandlerFactory_;
    protected $annotationReaderFactory_;
    protected $dbms_;
    protected $beanClass_;
    protected $beanMetaData_;
    protected $sqlCommands_;
    protected $daoSuffixes_ = array('Dao');

    public function __construct(ReflectionClass $daoClass,
                                S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null,
                                S2Dao_ResultSetFactory $resultSetFactory = null,
                                S2Dao_FieldAnnotationReaderFactory $annoReaderFactory = null){

        if(null == $annoReaderFactory){
            $annoReaderFactory = new S2Dao_FieldAnnotationReaderFactory();
        }
        
        $this->sqlCommands_ = new S2Dao_HashMap();
        $this->daoClass_ = $daoClass;
        $this->daoBeanDesc_ = S2Container_BeanDescFactory::getBeanDesc($daoClass);
        $this->daoInterface_ = self::getDaoInterface($daoClass);
        $this->annotationReaderFactory_ = $annoReaderFactory;
        $this->annotationReader_ = $annoReaderFactory->createDaoAnnotationReader($this->daoBeanDesc_);
        $this->beanClass_ = $this->annotationReader_->getBeanClass();
        $this->dataSource_ = $dataSource;
        $conn = $dataSource->getConnection();
        $this->dbms_ = S2DaoDbmsManager::getDbms($conn);
        $this->beanMetaData_ = $this->createBeanMetaData($this->beanClass_, $conn);
        $this->statementFactory_ = $statementFactory;
        $this->resultSetFactory_ = $resultSetFactory;
        $this->resultSetHandlerFactory_ = $this->createResultsetHandlerFactory();
        $this->setupSqlCommand();
    }

    protected function setupSqlCommand() {
        $idbd = S2Container_BeanDescFactory::getBeanDesc($this->daoInterface_);
        $names = $idbd->getMethodNames();
        $c = count($names);
        for ($i = 0; $i < $c; ++$i) {
            $methods = $this->daoBeanDesc_->getMethods($names[$i]);
            if (S2Container_MethodUtil::isAbstract($methods)) {
                $this->setupMethod($methods);
            }
        }
    }

    protected function setupMethod(ReflectionMethod $method) {
        $this->setupMethodByAnnotation($this->daoInterface_, $method);

        if (!$this->completedSetupMethod($method)) {
            $this->setupMethodBySqlFile($this->daoInterface_, $method);
        }
        if (!$this->completedSetupMethod($method)) {
            $this->setupMethodByInterfaces($this->daoInterface_, $method);
        }
        if (!$this->completedSetupMethod($method)) {
            $this->setupMethodBySuperClass($this->daoInterface_, $method);
        }
        if (!$this->completedSetupMethod($method)) {
            $this->setupMethodByAuto($method);
        }
    }
    
    protected function setupMethodByAnnotation(ReflectionClass $daoInterface,
                                               ReflectionMethod $method) {
        $sql = $this->annotationReader_->getSQL($method, $this->dbms_->getSuffix());
        if ($sql != null) {
            $this->setupMethodByManual($method, $sql);
        }
        $procedureName = $this->annotationReader_->getStoredProcedureName($method);
        if ($procedureName != null) {
            $returnType = $this->annotationReader_->getReturnType($method);
            $procedureHandler = null;
            if ($returnType == S2Dao_DaoAnnotationReader::RETURN_MAP) {
                $procedureHandler = new S2Dao_MapBasicProcedureHandler($this->dataSource_,
                                                                       $procedureName);
            } else {
                $procedureHandler = new S2Dao_ObjectBasicProcedureHandler($this->dataSource_,
                                                                          $procedureName);
            }
            $this->sqlCommands_->put($method->getName(),
                        new S2Dao_StaticStoredProcedureCommand($procedureHandler)
                        );
        }
    }
    
    protected function setupMethodBySqlFile(ReflectionClass $daoInterface,
                                            ReflectionMethod $method) {
        $base = dirname($this->daoInterface_->getFileName()) .
                DIRECTORY_SEPARATOR .
                $this->daoInterface_->getName() . '_' . $method->getName();
        $dbmsPath = $base . $this->dbms_->getSuffix() . '.sql';
        $standardPath = $base . '.sql';

        if (file_exists($dbmsPath)) {
            $sql = file_get_contents($dbmsPath);
            $this->setupMethodByManual($method, $sql);
        } else if (file_exists($standardPath)) {
            $sql = file_get_contents($standardPath);
            $this->setupMethodByManual($method, $sql);
        }
    }
    
    protected function setupMethodByInterfaces(ReflectionClass $daoInterface,
                                               ReflectionMethod $method) {
        $interfaces = $daoInterface->getInterfaces();
        if ($interfaces === null) {
            return;
        }
        
        $interfaces = array_values($interfaces);
        $c = count($interfaces);
        for ($i = 0; $i < $c; $i++) {
            $interfaceMethod = $this->getSameSignatureMethod($interfaces[$i], $method);
            if ($interfaceMethod != null) {
                $this->setupMethod($interfaces[$i], $interfaceMethod);
            }
        }
    }
    
    protected function setupMethodBySuperClass(ReflectionClass $daoInterface,
                                               ReflectionMethod $method) {
        
        $superDaoClass = $this->daoInterface_->getParentclass();
        if ($superDaoClass != null && !is_object($superDaoClass)) {
            $superClassMethod = $this->getSameSignatureMethod($superDaoClass, $method);
            if ($superClassMethod !== null) {
                $this->setupMethod($superDaoClass, $method);
            }
        }
    }
    
    protected function setupMethodByAuto(ReflectionMethod $method) {
        $methodName = $method->getName();
        if ($this->isInsert($methodName)) {
            $this->setupInsertMethodByAuto($method);
        } else if ($this->isUpdate($methodName)) {
            $this->setupUpdateMethodByAuto($method);
        } else if ($this->isDelete($methodName)) {
            $this->setupDeleteMethodByAuto($method);
        } else if($this->isSaveOrInsert($methodName)){
            $this->setupInsertOrUpdateByAuto($method);
        } else if($this->isDeleteAfterInsert($methodName)){
            $this->setupDeleteAfterInsertByAuto($method);
        } else {
            $this->setupSelectMethodByAuto($method);
        }
    }

    protected function setupSelectMethodByManual(ReflectionMethod $method, $sql) {
        $cmd = $this->createSelectDynamicCommand($this->createResultSetHandler($method));
        $cmd->setSql($sql);
        $cmd->setArgNames($this->annotationReader_->getArgNames($method));
        $this->sqlCommands_->put($method->getName(), $cmd);
    }

    protected function setupMethodByManual(ReflectionMethod $method, $sql) {
        if ($this->isSelect($method)) {
            $this->setupSelectMethodByManual($method, $sql);
        } else {
            $this->setupUpdateMethodByManual($method, $sql);
        }
    }
    
    protected function setupUpdateMethodByManual(ReflectionMethod $method, $sql) {
        $cmd = new S2Dao_UpdateDynamicCommand($this->dataSource_, $this->statementFactory_);
        $cmd->setSql($sql);
        $argNames = $this->annotationReader_->getArgNames($method);
        if(count($argNames) == 0 && $this->isUpdateSignatureForBean($method)) {
            $argNames = ucwords(get_class($this->beanClass_));
        }
        $cmd->setArgNames($argNames);
        $cmd->setNotSingleRowUpdatedExceptionClass(
                $this->getNotSingleRowUpdatedExceptionClass($method)
            );
        $this->sqlCommands_->put($method->getName(), $cmd);
    }

    protected function setupInsertMethodByAuto(ReflectionMethod $method) {
        $this->checkAutoUpdateMethod($method);
        $propertyNames = $this->getPersistentPropertyNames($method);
        $cmd = new S2Dao_InsertAutoStaticCommand($this->dataSource_,
                                           $this->statementFactory_,
                                           $this->beanMetaData_,
                                           $propertyNames);
        $this->sqlCommands_->put($method->getName(), $cmd);
    }

    protected function setupUpdateMethodByAuto(ReflectionMethod $method) {
        $this->checkAutoUpdateMethod($method);
        $propertyNames = $this->getPersistentPropertyNames($method);
        $cmd = new S2Dao_UpdateAutoStaticCommand($this->dataSource_,
                                           $this->statementFactory_,
                                           $this->beanMetaData_,
                                           $propertyNames);
        $this->sqlCommands_->put($method->getName(), $cmd);
    }

    protected function setupDeleteMethodByAuto(ReflectionMethod $method) {
        $this->checkAutoUpdateMethod($method);
        $propertyNames = $this->getPersistentPropertyNames($method);
        $cmd = new S2Dao_DeleteAutoStaticCommand($this->dataSource_,
                                           $this->statementFactory_,
                                           $this->beanMetaData_,
                                           $propertyNames);
        $this->sqlCommands_->put($method->getName(), $cmd);
    }
    
    protected function setupInsertOrUpdateByAuto(ReflectionMethod $method) {
        $this->setupSelectMethodByAuto($method);
        $propertyNames = $this->getPersistentPropertyNames($method);
        $sqlCommand = $this->sqlCommands_->remove($method->getName());
        $cmd = new S2Dao_InsertOrUpdateAutoStaticCommand($this->dataSource_,
                                            $this->statementFactory_,
                                            $this->beanMetaData_,
                                            $propertyNames);
        $cmd->setSql($sqlCommand->getSql());
        $cmd->setArgNames($sqlCommand->getArgNames());
        $this->sqlCommands_->put($method->getName(), $cmd);
    }
    
    protected function setupDeleteAfterInsertByAuto(ReflectionMethod $method) {
    }
    
    protected function setupSelectMethodByAuto(ReflectionMethod $method) {
        $query = $this->annotationReader_->getQuery($method);
        $handler = $this->createResultSetHandler($method);
        $cmd = null;
        $argNames = $this->annotationReader_->getArgNames($method);
        
        if ($query !== null && !self::startsWithOrderBy($query)) {
            $cmd = $this->createSelectDynamicCommand($handler, $query);
        } else {
            $cmd = $this->createSelectDynamicCommand($handler);
            $sql = null;

            // FIXME
            for($i = 0; $i < count($argNames); $i++){
                if(!$this->beanMetaData_->hasPropertyTypeByColumnName($argNames[$i])){
                    $argNames = array();
                    break;
                }
            }

            $params = $method->getParameters();
            if (count($argNames) == 0 && count($params) == 1) {
                $argNames = array('dto');
                $sql = $this->createAutoSelectSqlByDto($params[0]->getClass());
            } else {
                $sql = $this->createAutoSelectSql($argNames);
            }
            if ($query !== null) {
                if(stripos($sql, 'WHERE') === false && !self::startsWithOrderBy($query)){
                    $query = ' WHERE ' . $query;
                }
                $sql .= ' ' . $query;
            }
            $cmd->setSql($sql);
        }
        $cmd->setArgNames($argNames);
        $this->sqlCommands_->put($method->getName(), $cmd);
    }
    
    protected function completedSetupMethod(ReflectionMethod $method) {
        return $this->sqlCommands_->get($method->getName()) != null;
    }
    
    public function setAnnotationReaderFactory(
            S2Dao_AnnotationReaderFactory $annotationReaderFactory) {
        $this->annotationReaderFactory_ = $annotationReaderFactory;
    }

    public function setResultSetFactory(S2Dao_ResultSetFactory $resultSetFactory) {
        $this->resultSetFactory_ = $resultSetFactory;
    }

    public function setStatementFactory(S2Dao_StatementFactory $statementFactory) {
        $this->statementFactory_ = $statementFactory;
    }

    public function setDbms(S2Dao_Dbms $dbms) {
        $this->dbms_ = $dbms;
    }
    
    public function setDataSource(S2Container_DataSource $dataSource) {
        $this->dataSource_ = $dataSource;
    }

    protected function getDaoClass() {
        return $this->daoClass_;
    }

    public function setDaoClass(ReflectionClass $daoClass) {
        $this->daoClass_ = $daoClass;
    }

    public function setBeanClass(ReflectionClass $class){
        $this->daoClass_ = $class;
    }

    public function getBeanClass() {
        return $this->daoClass_;
    }
    
    public function getBeanMetaData() {
        return $this->beanMetaData_;
    }
    
    private function getSameSignatureMethod(ReflectionClass $clazz,
                                            ReflectionMethod $method) {
        try {
            return S2Container_ClassUtil::getMethod($clazz, $method->getName());
        } catch (S2Container_NoSuchMethodRuntimeException $e) {
            return null;
        }
    }
    
    protected function getAnnotationReaderFactory() {
        return $this->annotationReaderFactory_;
    }

    protected function getNotSingleRowUpdatedExceptionClass(ReflectionMethod $method) {
        return null;
    }    

    protected function getPersistentPropertyNames(ReflectionMethod $method) {
        $names = new S2Dao_ArrayList();
        $props = $this->annotationReader_->getNoPersistentProps($method);
        if ($props != null) {
            for ($i = 0; $i < $this->beanMetaData_->getPropertyTypeSize(); ++$i) {
                $pt = $this->beanMetaData_->getPropertyType($i);
                if ($pt->isPersistent()
                      && !$this->isPropertyExist((array)$props, $pt->getPropertyName())) {
                    $names->add($pt->getPropertyName());
                }
            }
        } else {
            $props = $this->annotationReader_->getPersistentProps($method);
            if($props != null){
                $names->addAll(new S2Dao_ArrayList($props));
                for ($i = 0; $i < $this->beanMetaData_->getPrimaryKeySize(); ++$i) {
                    $pk = $this->beanMetaData_->getPrimaryKey($i);
                    $pt = $this->beanMetaData_->getPropertyTypeByColumnName($pk);
                    $names->add($pt->getPropertyName());
                }
                if ($this->beanMetaData_->hasVersionNoPropertyType()) {
                    $names->add($this->beanMetaData_->getVersionNoPropertyName());
                }
                if ($this->beanMetaData_->hasTimestampPropertyType()) {
                    $names->add($this->beanMetaData_->getTimestampPropertyName());
                }
            }
        }
        if ($names->size() == 0) {
            for ($i = 0; $i < $this->beanMetaData_->getPropertyTypeSize(); ++$i) {
                $pt = $this->beanMetaData_->getPropertyType($i);
                if ($pt->isPersistent()) {
                    $names->add($pt->getPropertyName());
                }
            }
        }
        return $names->toArray();
    }

    public function getSqlCommand($methodName){
        $cmd = $this->sqlCommands_->get($methodName);
        if ($cmd === null) {
            throw new S2Container_MethodNotFoundRuntimeException($this->daoClass_,
                                                                 $methodName,
                                                                 null);
        }
        return $cmd;
    }

    public function getDaoInterface(ReflectionClass $clazz) {
        if ($clazz->isInterface()) {
            return $clazz;
        }
        for($target = $clazz;
            !($target instanceof S2Dao_AbstractDao); $target = $target->getParentClass()){
            $interfaces = $target->getInterfaces();
            foreach($interfaces as $intf) {
                $c = count($this->daoSuffixes_);
                for($j = 0; $j < $c; $j++){
                    if(ereg($this->daoSuffixes_[$j] . '$', $intf->getName())) {
                        return $intf;
                    }
                }
            }
        }
        throw new S2Dao_DaoNotFoundRuntimeException($clazz);
    }
    
    protected function createSelectDynamicCommand(S2Dao_ResultSetHandler $rsh, $query = null) {
        if($query === null){
            return new S2Dao_SelectDynamicCommand($this->dataSource_,
                                                 $this->statementFactory_,
                                                 $rsh,
                                                 $this->resultSetFactory_);
        } else {
            $cmd = $this->createSelectDynamicCommand($rsh);
            $buf = '';
            if (self::startsWithSelect($query)) {
                $buf .= $query;
            } else {
                $sql = $this->dbms_->getAutoSelectSql($this->getBeanMetaData());
                $buf .= $sql;
                if (self::startsWithOrderBy($query)) {
                    $buf .= ' ';
                } else if (self::startsWithBeginComment($query)) {
                    $buf .= ' ';
                } else if (stripos($sql, 'WHERE') === false) {
                    $buf .= ' WHERE ';
                } else {
                    $buf .= ' AND ';
                }
                $buf .= $query;
            }
            $cmd->setSql($buf);
            return $cmd;
        }
    }

    protected function createAutoSelectSqlByDto($dtoClass) {
        $sql = $this->dbms_->getAutoSelectSql($this->getBeanMetaData());
        if(!is_object($dtoClass)){
            return $sql;
        }
        
        $buf = $sql;
        $dmd = $this->createDtoMetaData($dtoClass);

        $began = false;
        if (stripos($sql, 'WHERE') === false) {
            $buf .= '/*BEGIN*/ WHERE ';
            $began = true;
        }
        
        $c = $dmd->getPropertyTypeSize();
        for ($i = 0; $i < $c; ++$i) {
            $pt = $dmd->getPropertyType($i);
            $aliasName = $pt->getColumnName();
            if (!$this->beanMetaData_->hasPropertyTypeByAliasName($aliasName)) {
                continue;
            }
            if (!$this->beanMetaData_->getPropertyTypeByAliasName($aliasName)->isPersistent()) {
                continue;
            }
            if($dmd->hasPropertyType($aliasName)){
                continue;
            }
            $columnName = $this->beanMetaData_->convertFullColumnName($aliasName);
            $propertyName = 'dto.' . $pt->getPropertyName();
            $buf .= '/*IF ' . $propertyName . ' !== null*/';
            $buf .= ' ';
            if (!$began || $i != 0) {
                $buf .= 'AND ';
            }
            $buf .= $columnName . ' = /*' . $propertyName . '*/null';
            $buf .= '/*END*/';
        }
        if ($began) {
            $buf .= '/*END*/';
        }
        return $buf;
    }    

    protected function createAutoSelectSql(array $argNames) {
        $sql = $this->dbms_->getAutoSelectSql($this->getBeanMetaData());
        $buf = $sql;

        $c = count($argNames);
        if ($c != 0) {
            $began = false;
            if (stripos($sql, 'WHERE') === false) {
                $buf .= '/*BEGIN*/ WHERE ';
                $began = true;
            }
            for($i = 0; $i < $c; $i++){
                $columnName = $this->beanMetaData_->convertFullColumnName($argNames[$i]);
                $buf .= '/*IF ' . $argNames[$i] . ' !== null*/';
                $buf .= ' ';
                if (!$began || $i != 0) {
                    $buf .= 'AND ';
                }
                $buf .= $columnName . ' = /*' . $argNames[$i] . '*/null';
                $buf .= '/*END*/';
            }
            if ($began) {
                $buf .= '/*END*/';
            }
        }
        return $buf;
    }
    
    protected function createBeanMetaData(ReflectionClass $beanClass, PDO $conn){
        return new S2Dao_BeanMetaDataImpl($beanClass,
                                          $conn,
                                          $this->dbms_,
                                          $this->annotationReaderFactory_);
    }

    protected function createDtoMetaData($dtoClass) {
        $dtoMetaData = new S2Dao_DtoMetaDataImpl($dtoClass,
                    $this->getAnnotationReaderFactory()->createBeanAnnotationReader($dtoClass));
        $dtoMetaData->initialize();
        return $dtoMetaData;
    }
    
    protected function createResultSetHandlerFactory(){
        return new S2Dao_ResultSetHandlerFactoryImpl($this->beanMetaData_,
                                                     $this->annotationReader_);
    }
    
    protected function createResultSetHandler(ReflectionMethod $method) {
        return $this->resultSetHandlerFactory_->createResultSetHandler($method);
    }

    public function createFindCommand($query) {
        return $this->createSelectDynamicCommand(
               new S2Dao_BeanListMetaDataResultSetHandler($this->beanMetaData_), $query);
    }

    public function createFindArrayCommand($query) {
        return $this->createSelectDynamicCommand(
               new S2Dao_BeanArrayMetaDataResultSetHandler($this->beanMetaData_), $query);
    }

    public function createFindBeanCommand($query) {
        return $this->createSelectDynamicCommand(
               new S2Dao_BeanMetaDataResultSetHandler($this->beanMetaData_), $query);
    }
    
    public function createFindYamlCommand($query) {
        return $this->createSelectDynamicCommand(
               new S2Dao_BeanYamlMetaDataResultSetHandler($this->beanMetaData_), $query);
    }
    
    public function createFindJsonCommand($query) {
        return $this->createSelectDynamicCommand(
               new S2Dao_BeanJsonMetaDataResultSetHandler($this->beanMetaData_), $query);
    }

    public function createFindObjectCommand($query) {
        return $this->createSelectDynamicCommand(new S2Dao_ObjectResultSetHandler(), $query);
    }

    protected function checkAutoUpdateMethod(ReflectionMethod $method) {
        $types = $method->getParameters();
        if (count($types) != 1
                || !$this->isBeanClassAssignable($types[0])
                && !$types[0] instanceof S2Dao_ArrayList
                && !is_array($types)) {
            throw new S2Dao_IllegalSignatureRuntimeException('EDAO0006',
                                            (string)$method->__toString());
        }
    }
    
    protected static function startsWithSelect($query = null) {
        if ($query == null) {
            return false;
        }
        return preg_match(self::startWithSelectPattern, trim($query));
    }

    protected static function startsWithBeginComment($query = null) {
        if($query == null){
            return false;
        }
        return preg_match(self::startWithBeginCommentPattern, trim($query));
    }

    protected static function startsWithOrderBy($query = null) {
        if ($query == null) {
            return false;
        }
        return preg_match(self::startWithOrderByPattern, trim($query));
    }

    protected function isBeanClassAssignable($clazz = null) {
        if($clazz === null){
            return false;
        }
        if($clazz instanceof ReflectionClass){
            return true;
        } else if($clazz instanceof ReflectionMethod){
            return $this->isBeanClassAssignable($clazz->getDeclaringClass());
        } else if($clazz instanceof ReflectionParameter){
            return $this->isBeanClassAssignable($clazz->getClass());
        }

        return true;
    }

    protected function isUpdateSignatureForBean(ReflectionMethod $method) {
        $types = $method->getParameters();
        return count($types) == 1 && $this->isBeanClassAssignable($types[0]);
    }

    protected function isPropertyExist(array $props, $propertyName) {
        $c = count($props);
        for ($i = 0; $i < $c; ++$i) {
            if(strcasecmp($props[$i], $propertyName) == 0){
                return true;
            }
        }
        return false;
    }

    protected function isSelect(ReflectionMethod $method) {
        if ($this->isInsert($method->getName())) {
            return false;
        }
        if ($this->isUpdate($method->getName())) {
            return false;
        }
        if ($this->isDelete($method->getName())) {
            return false;
        }
        return true;
    }

    protected function isInsert($methodName) {
        return preg_match(self::INSERT_NAMES,$methodName);
    }
    
    protected function isUpdate($methodName) {
        return preg_match(self::UPDATE_NAMES, $methodName);
    }

    protected function isDelete($methodName) {
        return preg_match(self::DELETE_NAMES, $methodName);
    }

    public function hasSqlCommand($methodName) {
        return $this->sqlCommands_->containsKey($methodName);
    }

}
?>
