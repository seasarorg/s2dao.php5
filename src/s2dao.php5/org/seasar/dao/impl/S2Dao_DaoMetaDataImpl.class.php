<?php

/**
 * @author nowel
 */
class S2Dao_DaoMetaDataImpl implements S2Dao_DaoMetaData {

    const INSERT_NAMES = '/^(insert|create|add)/i';
    const UPDATE_NAMES = '/^(update|modify|store)/i';
    const DELETE_NAMES = '/^(delete|remove)/i';
    const NOT_SINGLE_ROW_UPDATED = 'NotSingleRowUpdated';
    const startWithSelectPattern = '/^SELECT/i';
    const startWithOrderByPattern = '/(\/\*[^\*]+\*\/)*order by/i';

    protected $daoClass_;
    protected $daoInterface_;
    protected $daoBeanDesc_;
    protected $dataSource_;
    protected $annotationReader_;
    protected $statementFactory_;
    protected $resultSetFactory_;
    protected $annotationReaderFactory_;
    protected $dbms_;
    protected $beanClass_;
    protected $beanMetaData_;
    protected $sqlCommands_;
    protected $daoSuffixes_ = array('Dao');

    public function __construct(ReflectionClass $daoClass,
                              S2Container_DataSource $dataSource,
                              S2Dao_StatementFactory $statementFactory,
                              S2Dao_ResultSetFactory $resultSetFactory,
                              S2Dao_FieldAnnotationReaderFactory $annotationReaderFactory = null){

        if(null == $annotationReaderFactory){
            $annotationReaderFactory = new S2Dao_FieldAnnotationReaderFactory();
        }

        $this->sqlCommands_ = new S2Dao_HashMap();
        $this->daoClass_ = $daoClass;
        $this->daoBeanDesc_ = S2Container_BeanDescFactory::getBeanDesc($daoClass);
        $this->daoInterface_ = self::getDaoInterface($daoClass);
        $this->annotationReaderFactory_ = $annotationReaderFactory;
        $this->annotationReader_ =
                    $annotationReaderFactory->createDaoAnnotationReader($this->daoBeanDesc_);
        $this->beanClass_ = $this->annotationReader_->getBeanClass();
        $this->dataSource_ = $dataSource;
        $this->statementFactory_ = $statementFactory;
        $this->resultSetFactory_ = $resultSetFactory;
        $conn = $this->dataSource_->getConnection();
        $this->dbms_ = S2Dao_DbmsManager::getDbms($conn);
        $this->beanMetaData_ = new S2Dao_BeanMetaDataImpl($this->beanClass_,
                                                          $conn,
                                                          $this->dbms_,
                                                          $annotationReaderFactory);
        $this->setupSqlCommand();
    }

    protected function setupSqlCommand() {
        $idbd = S2Container_BeanDescFactory::getBeanDesc($this->daoInterface_);
        $names = $idbd->getMethodNames();
        $c = count($names);
        for ($i = 0; $i < $c; ++$i) {
            $method = $this->daoBeanDesc_->getMethods($names[$i]);
            if (S2Container_MethodUtil::isAbstract($method)) {
                $this->setupMethod($method);
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
            $returnType = $this->annotationReader_->getReturnType();
            if ($returnType == 'Map') {
                $this->sqlCommands_->put($method->getName(),
                        new S2Dao_StaticStoredProcedureCommand(
                                new S2Dao_MapBasicProcedureHandler(
                                        $this->dataSource_,
                                        $procedureName)));
            } else {
                $this->sqlCommands_->put($method->getName(),
                        new S2Dao_StaticStoredProcedureCommand(
                                new S2Dao_ObjectBasicProcedureHandler(
                                        $this->dataSource_,
                                        $procedureName)));
            }
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
        /*
        else {
            $this->setupMethodByAuto($method);
        }
        */
    }
    
    protected function setupMethodByInterfaces(ReflectionClass $daoInterface,
                                               ReflectionMethod $method) {
        $interfaces = $daoInterface->getInterfaces();
        if ($interfaces == null) {
            return;
        }
        $c = count($interfaces);
        for ($i = 0; $i < $c; $i++) {
            $interfaceMethod = $this->getSameSignatureMethod($interfaces[$i],$method);
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
    
    protected function completedSetupMethod(ReflectionMethod $method) {
        return $this->sqlCommands_->get($method->getName()) !== null;
    }
    
    private function getSameSignatureMethod(ReflectionClass $clazz,
                                            ReflectionMethod $method) {
        try {
            return S2Container_ClassUtil::getMethod($clazz, $method->getName());
        } catch (S2Container_NoSuchMethodRuntimeException $e) {
            return null;
        }
    }

    protected function setupMethodByManual(ReflectionMethod $method, $sql) {
        if ($this->isSelect($method)) {
            $this->setupSelectMethodByManual($method, $sql);
        } else {
            $this->setupUpdateMethodByManual($method, $sql);
        }
    }

    protected function setupMethodByAuto(ReflectionMethod $method) {
        if ($this->isInsert($method->getName())) {
            $this->setupInsertMethodByAuto($method);
        } else if ($this->isUpdate($method->getName())) {
            $this->setupUpdateMethodByAuto($method);
        } else if ($this->isDelete($method->getName())) {
            $this->setupDeleteMethodByAuto($method);
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

    protected function createSelectDynamicCommand(S2Dao_ResultSetHandler $rsh, $query = null) {
        if($query == null){
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
                if ($query != null) {
                    if (self::startsWithOrderBy($query)) {
                        $buf .= ' ';
                    } else if (stripos($sql, 'WHERE') === false) {
                        $buf .= ' WHERE ';
                    } else {
                        $buf .= ' AND ';
                    }
                    $buf .= $query;
                }
            }
            $cmd->setSql($buf);
            return $cmd;
        }
    }

    protected static function startsWithSelect($query = null) {
        if ($query == null) {
            return false;
        }
        return preg_match(self::startWithSelectPattern, trim($query));
    }

     protected static function startsWithOrderBy($query = null) {
        if ($query == null) {
            return false;
        }
        return preg_match(self::startWithOrderByPattern, $query);
    }

    protected function createResultSetHandler(ReflectionMethod $method) {
        if($this->isSelectList($method)){
            return new S2Dao_BeanListMetaDataResultSetHandler($this->beanMetaData_);
        } else if( $this->isSelectArray($method) ){
            return new S2Dao_BeanArrayMetaDataResultSetHandler($this->beanMetaData_);
        } else if( $this->isBeanClassAssignable($method) ){
            return new S2Dao_BeanMetaDataResultSetHandler($this->beanMetaData_);
        } else {
            return new S2Dao_ObjectResultSetHandler();
        }
    }

    protected function isBeanClassAssignable($clazz = null) {
        if($clazz === null){
            return false;
        }
        if($clazz instanceof ReflectionClass){
//            return $this->beanClass_->isSubclassOf($clz->getName()) ||
//                   $clz->isSubclassOf($this->beanClass_->getName());
            return $clazz;
        } else if($clazz instanceof ReflectionMethod){
            return $this->isBeanClassAssignable($clazz->getDeclaringClass());
        } else if($clazz instanceof ReflectionParameter){
            return $this->isBeanClassAssignable($clazz->getClass());
        }

        return true;
    }

    protected function setupUpdateMethodByManual(ReflectionMethod $method, $sql) {
        $cmd = new S2Dao_UpdateDynamicCommand($this->dataSource_, $this->statementFactory_);
        $cmd->setSql($sql);
        $argNames = $this->annotationReader_->getArgNames($method);
        if(count($argNames) == 0 && $this->isUpdateSignatureForBean($method)) {
            $argNames = array(S2Container_StringUtil::decapitalize(
                                S2Container_ClassUtil::getShortClassName($this->beanClass_))
                             );
        }
        $cmd->setArgNames($argNames);
        $cmd->setNotSingleRowUpdatedExceptionClass(
                $this->getNotSingleRowUpdatedExceptionClass($method)
            );
        $this->sqlCommands_->put($method->getName(), $cmd);
    }

    protected function isUpdateSignatureForBean(ReflectionMethod $method) {
        $types = $method->getParameters();
        return count($types) == 1 && $this->isBeanClassAssignable($types[0]);
    }

    protected function getNotSingleRowUpdatedExceptionClass(ReflectionMethod $method) {
        return null;
    }

    protected function setupInsertMethodByAuto(ReflectionMethod $method) {
        $this->checkAutoUpdateMethod($method);
        $propertyNames = $this->getPersistentPropertyNames($method);
        $cmd = null;
        if ($this->isUpdateSignatureForBean($method)) {
            $cmd = new S2Dao_InsertAutoStaticCommand($this->dataSource_,
                                               $this->statementFactory_,
                                               $this->beanMetaData_,
                                               $propertyNames);
        } else {
            $cmd = new S2Dao_InsertBatchAutoStaticCommand($this->dataSource_,
                                                    $this->statementFactory_,
                                                    $this->beanMetaData_,
                                                    $propertyNames);
        }
        $this->sqlCommands_->put($method->getName(), $cmd);
    }

    protected function setupUpdateMethodByAuto(ReflectionMethod $method) {
        $this->checkAutoUpdateMethod($method);
        $propertyNames = $this->getPersistentPropertyNames($method);
        $cmd = null;
        if ($this->isUpdateSignatureForBean($method)) {
            $cmd = new S2Dao_UpdateAutoStaticCommand($this->dataSource_,
                                               $this->statementFactory_,
                                               $this->beanMetaData_,
                                               $propertyNames);
        } else {
            $cmd = new S2Dao_UpdateBatchAutoStaticCommand($this->dataSource_,
                                                    $this->statementFactory_,
                                                    $this->beanMetaData_,
                                                    $propertyNames);
        }
        $this->sqlCommands_->put($method->getName(), $cmd);
    }

    protected function setupDeleteMethodByAuto(ReflectionMethod $method) {
        $this->checkAutoUpdateMethod($method);
        $propertyNames = $this->getPersistentPropertyNames($method);
        $cmd = null;
        if ($this->isUpdateSignatureForBean($method)) {
            $cmd = new S2Dao_DeleteAutoStaticCommand($this->dataSource_,
                                               $this->statementFactory_,
                                               $this->beanMetaData_,
                                               $propertyNames);
        } else {
            $cmd = new S2Dao_DeleteBatchAutoStaticCommand($this->dataSource_,
                                                    $this->statementFactory_,
                                                    $this->beanMetaData_,
                                                    $propertyNames);
        }
        $this->sqlCommands_->put($method->getName(), $cmd);
    }

    protected function getPersistentPropertyNames(ReflectionMethod $method) {
        $names = new S2Dao_ArrayList();
        $props = $this->annotationReader_->getNoPersistentProps($method);
        if ($props != null) {
            for ($i = 0; $i < $this->beanMetaData_->getPropertyTypeSize(); ++$i) {
                $pt = $this->beanMetaData_->getPropertyType($i);
                if ($pt->isPersistent()
                      && !$this->isPropertyExist($props, $pt->getPropertyName())) {
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

    protected function isPropertyExist(array $props, $propertyName) {
        $c = count($props);
        for ($i = 0; $i < $c; ++$i) {
            if(strcasecmp($props[$i], $propertyName) == 0){
                return true;
            }
        }
        return false;
    }

    protected function setupSelectMethodByAuto(ReflectionMethod $method) {
        $query = $this->annotationReader_->getQuery($method);
        $handler = $this->createResultSetHandler($method);
        $cmd = null;
        $argNames = $this->annotationReader_->getArgNames($method);
        
        if ($query != null && !self::startsWithOrderBy($query)) {
            $cmd = $this->createSelectDynamicCommand($handler, $query);
        } else {
            $cmd = $this->createSelectDynamicCommand($handler);
            $sql = null;

            // FIXME
            $param = $method->getParameters();
            if (count($argNames) == 0 && count($param) == 1) {
                // $argNames = array('dto');
                $sql = $this->createAutoSelectSqlByDto($param[0]->getClass());
            } else {
                //$sql = $this->createAutoSelectSql($argNames);
                $sql = $this->createAutoSelectSql(array());
            }
            if ($query != null) {
                $sql .= ' ' . $query;
            }
            $cmd->setSql($sql);
        }
        $cmd->setArgNames($argNames);
        $this->sqlCommands_->put($method->getName(), $cmd);
    }

    protected function createAutoSelectSqlByDto($dtoClass) {
        $sql = $this->dbms_->getAutoSelectSql($this->getBeanMetaData());
        if(!is_object($dtoClass)){
            return $sql;
        }
        
        $buf = $sql;
        $dmd = new S2Dao_DtoMetaDataImpl($dtoClass,
                $this->annotationReaderFactory_->createBeanAnnotationReader($dtoClass));

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
            /*
            if (!is_object($this->beanMetaData_->getPropertyTypeByAliasName($aliasName))){
                continue;
            }
            */
            if (!$this->beanMetaData_->getPropertyTypeByAliasName($aliasName)->isPersistent()) {
                continue;
            }
            $columnName = $this->beanMetaData_->convertFullColumnName($aliasName);
            $propertyName = 'dto.' . $pt->getPropertyName();
            $buf .= '/*IF ';
            $buf .= $propertyName;
            $buf .= ' != null*/';
            $buf .= ' ';
            if (!$began || $i != 0) {
                $buf .= 'AND ';
            }
            $buf .= $columnName;
            $buf .= ' = /*';
            $buf .= $propertyName;
            $buf .= '*/null';
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

        if (count($argNames) != 0) {
            $began = false;
            if (stripos($sql, 'WHERE') === false) {
                $buf .= '/*BEGIN*/ WHERE ';
                $began = true;
            }
            foreach($argNames as $key => $argName){
                $columnName = $this->beanMetaData_->convertFullColumnName($argName);
                $buf .= '/*IF ';
                $buf .= $argName;
                $buf .= ' != null*/';
                $buf .= ' ';
                if (!$began || $key != 0) {
                    $buf .= 'AND ';
                }
                $buf .= $columnName;
                $buf .= ' = /*';
                $buf .= $argName;
                $buf .= '*/null';
                $buf .= '/*END*/';
            }
            if ($began) {
                $buf .= '/*END*/';
            }
        }
        return $buf;
    }

    protected function checkAutoUpdateMethod(ReflectionMethod $method) {
        $types = $method->getParameters();
        if (count($types) != 1
                || !$this->isBeanClassAssignable($types[0])
                && !$types[0] instanceof S2Dao_ArrayList
                && !is_array($types)) {
            throw new S2Dao_IllegalSignatureRuntimeException('EDAO0006',
                                            (string)$method->toString());
        }
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

    protected function isSelectArray(ReflectionMethod $method){
        return $this->annotationReader_->isSelectArray($method);
    }

    protected function isSelectList(ReflectionMethod $method){
        return $this->annotationReader_->isSelectList($method);
    }

    public function getBeanClass() {
        return $this->daoClass_;
    }

    public function getBeanMetaData() {
        return $this->beanMetaData_;
    }

    public function getSqlCommand($methodName){
        $cmd = $this->sqlCommands_->get($methodName);
        if ($cmd == null) {
            throw new S2Container_MethodNotFoundRuntimeException($this->daoClass_,
                                                                $methodName,
                                                                null);
        }
        return $cmd;
    }

    public function hasSqlCommand($methodName) {
        return $this->sqlCommands_->containsKey($methodName);
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

    public function createFindObjectCommand($query) {
        return $this->createSelectDynamicCommand(new S2Dao_ObjectResultSetHandler(), $query);
    }

    public function getDaoInterface(ReflectionClass $clazz) {
        if ($clazz->isInterface()) {
            return $clazz;
        }
        for($target = $clazz;
            !($target instanceof S2Dao_AbstractDao); $target = $target->getParentClass()){
            $interfaces = $target->getInterfaces();
            foreach($interfaces as $intf) {
                for($j = 0; $j < count($this->daoSuffixes_); $j++){
                    if(ereg($this->daoSuffixes_[$j].'$', $intf->getName())) {
                        return $intf;
                    }
                }
            }
        }
        throw new S2Dao_DaoNotFoundRuntimeException($clazz);
    }

    public function setDbms(S2Dao_Dbms $dbms) {
        $this->dbms_ = $dbms;
    }
}
?>