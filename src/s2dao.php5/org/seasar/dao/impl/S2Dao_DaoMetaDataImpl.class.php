<?php

/**
 * @author nowel
 */
class S2Dao_DaoMetaDataImpl implements S2Dao_DaoMetaData {

    private static $INSERT_NAMES = array("insert", "create", "add");
    private static $UPDATE_NAMES = array("update", "modify", "store");
    private static $DELETE_NAMES = array("delete", "remove");

    const SELECT_ARRAY_NAME = "/Array_/i";
    const SELECT_LIST_NAME = "/List_/i";

    const NOT_SINGLE_ROW_UPDATED = "NotSingleRowUpdated";
    const startWithOrderByPattern = "/(\/\*[^\*]+\*\/)+order by/i";

    protected $daoClass_;
    protected $daoInterface_;
    protected $daoBeanDesc_;
    protected $dataSource_;
    protected $annotationReader_;
    protected $statementFactory_;
    protected $resultSetFactory_;
    protected $dbms_;
    protected $beanClass_;
    protected $beanMetaData_;

    public function __construct($daoClass,
                                S2Container_DataSource $dataSource,
                                //SqlHandler $sqlHandler ){
                                $sqlHandler ){
                                //$statementFactory,
                                //$resultSetFactory) {

        $this->sqlCommands_ = new S2Dao_HashMap();
        $this->daoClass_ = $daoClass;
        $this->daoBeanDesc_ = S2Container_BeanDescFactory::getBeanDesc($daoClass);
        $this->daoInterface_ = self::getDaoInterface($daoClass);
        $this->annotationReader_ = new S2Dao_FieldAnnotationReader($this->daoBeanDesc_);
        $this->beanClass_ = $this->annotationReader_->getBeanClass();

        if($this->beanClass_ !== null){
            $this->resultSetHandler_ = new S2Container_BeanResultSetHandler($this->beanClass_);
        } else {
            $this->resultSetHandler_ = new S2Container_ArrayResultSetHandler();
        }

        $this->dataSource_ = $dataSource;
        //$this->statementFactory_ = $statementFactory;
        $con = $this->dataSource_->getConnection();
        $dbMetaData = S2Dao_ConnectionUtil::getMetaData($con);
        $this->dbms_ = S2Dao_DbmsManager::getDbms($dbMetaData);
        $this->beanMetaData_ = new S2Dao_BeanMetaDataImpl($this->beanClass_,
                                                    $dbMetaData,
                                                    $this->dbms_);
        $con->disconnect();
        $this->setupSqlCommand();
    }

    protected function setupSqlCommand() {
        $idbd = S2Container_BeanDescFactory::getBeanDesc($this->daoInterface_);
        $names = $idbd->getMethodNames();
        for ($i = 0; $i < count($names); ++$i) {
            $methods = $this->daoBeanDesc_->getMethods($names[$i]);
            if (count($methods) == 1 && S2Container_MethodUtil::isAbstract($methods)) {
                $this->setupMethod($methods);
            }
        }
    }

    protected function setupMethod(ReflectionMethod $method) {
        $sql = $this->annotationReader_->getSQL($method->getName(), $this->dbms_->getSuffix());
        if( $sql != null){
            $this->setupMethodByManual($method, $sql);
            return;
        }

        $base = dirname($this->daoInterface_->getFileName()) .
                DIRECTORY_SEPARATOR .
                $this->daoInterface_->getName() . "_" . $method->getName();

        $dbmsPath = $base . $this->dbms_->getSuffix() . ".sql";
        $standardPath = $base . ".sql";

        if ( file_exists($dbmsPath) ) {
            $sql = file_get_contents($dbmsPath);
            $this->setupMethodByManual($method, $sql);
        } else if ( file_exists($standardPath) ) {
            $sql = file_get_contents($standardPath);
            $this->setupMethodByManual($method, $sql);
        } else {
            $this->setupMethodByAuto($method);
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
        $cmd->setArgNames($this->annotationReader_->getArgNames($method->getName()));
        //$cmd->setArgTypes($method->getParameterTypes());
        $this->sqlCommands_->put($method->getName(), $cmd);
    }

    protected function createSelectDynamicCommand(S2Container_ResultSetHandler $rsh, $query = null) {
        if( $query == null ){
            return new S2Dao_SelectDynamicCommand($this->dataSource_,
                                                  $this->statementFactory_,
                                                  $rsh,
                                                  $this->resultSetFactory_);
        } else {
            $cmd = $this->createSelectDynamicCommand($rsh);
            $buf = "";
            if (self::startsWithSelect($query)) {
                $buf .= $query;
            } else {
                $sql = $this->dbms_->getAutoSelectSql($this->getBeanMetaData());
                $buf .= $sql;
                if ($query != null) {
                    if (self::startsWithOrderBy($query)) {
                        $buf .= " ";
                    } else if (strrpos($sql, "WHERE") < 0) {
                        $buf .= " WHERE ";
                    } else {
                        $buf .= " AND ";
                    }
                    $buf = preg_replace("/ AND $/", " ", $buf);
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
        return ereg("^select", trim($query));
    }

     protected static function startsWithOrderBy($query = null) {
        if ($query != null) {
            $match = "";
            if( preg_match(self::startWithOrderByPattern, $query, $match) ){
                return true;
            }
        }
        return false;
    }

    protected function createResultSetHandler(ReflectionMethod $method) {
        if( $this->isSelectList($method->getName()) ){
            return new S2Dao_BeanListMetaDataResultSetHandler($this->beanMetaData_);
        } else if( $this->isBeanClassAssignable($method->returnsReference()) ){
            return new S2Dao_BeanMetaDataResultSetHandler($this->beanMetaData_);
        } else if( $this->isSelectArray($method->getName()) ){
        	return new S2Dao_BeanArrayMetaDataResultSetHandler($this->beanMetaData_);
        } else {
            return new S2Dao_ObjectResultSetHandler();
        }
    }

    protected function isBeanClassAssignable($clazz = null) {
        if( is_object($clazz) ){
            $clz = $clazz->getClass();
            if( $clz == null ){
                return false;
            }
            return $this->beanClass_->isSubclassOf($clz->getName()) ||
                    $clz->isSubclassOf($this->beanClass_->getName());
        } else {
            return false;
        }
    }

    protected function setupUpdateMethodByManual(ReflectionMethod $method, $sql) {
        $cmd = new S2Dao_UpdateDynamicCommand($this->dataSource_, $this->statementFactory_);
        $cmd->setSql($sql);
        $argNames = $this->annotationReader_->getArgNames($method->getName());
        if (count($argNames) == 0 && $this->isUpdateSignatureForBean($method)) {
            $argNames = array(S2Container_StringUtil::decapitalize(
                                S2Container_ClassUtil::getShortClassName($this->beanClass_))
                             );
        }
        $cmd->setArgNames($argNames);
        //$cmd->setArgTypes($method->getParameterTypes());
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
        $exceptionTypes = $method->getExceptionTypes();
        if ($exceptionTypes != null) {
            foreach($exceptionTypes as $exceptionType){
                if( strpos($exceptionType->getName(), self::NOT_SINGLE_ROW_UPDATED) >= 0 ){
                    return $exceptionType;
                }
            }
        }
        return null;
    }

    protected function setupInsertMethodByAuto(ReflectionMethod $method) {
        $this->checkAutoUpdateMethod($method);
        $propertyNames = $this->getPersistentPropertyNames($method->getName());
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
        $propertyNames = $this->getPersistentPropertyNames($method->getName());
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
        $propertyNames = $this->getPersistentPropertyNames($method->getName());
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

    protected function getPersistentPropertyNames($methodName) {
        $names = new S2Dao_ArrayList();
        $props = $this->annotationReader_->getNoPersistentProps($methodName);
        if ($props != null) {
            for ($i = 0; $i < $this->beanMetaData_->getPropertyTypeSize(); ++$i) {
                $pt = $this->beanMetaData_->getPropertyType($i);
                if ($pt->isPersistent()
                        && !$this->isPropertyExist($props, $pt->getPropertyName())) {
                    $names->add($pt->getPropertyName());
                }
            }
        } else {
            $props = $this->annotationReader_->getPersistentProps($methodName);
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
                if ($pt !== null && $pt->isPersistent()) {
                    $names->add($pt->getPropertyName());
                }
            }
        }
        return $names->toArray();
    }

    protected function isPropertyExist($props, $propertyName) {
        for ($i = 0; $i < count($props); ++$i) {
            if( strcasecmp($props[$i], $propertyName) == 0 ){
                return true;
            }
        }
        return false;
    }

    protected function setupSelectMethodByAuto(ReflectionMethod $method) {
        $query = $this->annotationReader_->getQuery($method->getName());
        $handler = $this->createResultSetHandler($method);

        $cmd = null;
        $argNames = $this->annotationReader_->getArgNames($method->getName());

        if ($query != null && !self::startsWithOrderBy($query)) {
            $cmd = $this->createSelectDynamicCommand($handler, $query);
        } else {
            $cmd = $this->createSelectDynamicCommand($handler);
            $sql = null;
            if (count($argNames) == 0 && count($method->getParameters()) == 1) {
                //$argNames = array("dto");

                //$types = $method->getParameters();
            	$param = $method->getParameters();
            	$types = $param[0]->getClass();

            	if( $types !== null ){
                    $sql = $this->createAutoSelectSqlByDto($types[0]);
            	} else {
            		$sql = $this->createAutoSelectSql($argNames);
            	}
            } else {
                $sql = $this->createAutoSelectSql($argNames);
            }
            if ($query != null) {
                $sql .= " " . $query;
            }
            $cmd->setSql($sql);
        }
        $cmd->setArgNames($argNames);
        //$cmd->setArgTypes($method->getParameters());
        $this->sqlCommands_->put($method->getName(), $cmd);
    }

    protected function createAutoSelectSqlByDto($dtoClass) {
        $sql = $this->dbms_->getAutoSelectSql($this->getBeanMetaData());
        $buf = $sql;

        $dmd = new S2Dao_DtoMetaDataImpl($dtoClass);
        $began = false;

        if (!(strrpos($sql,"WHERE") > 0)) {
            $buf .= "/*BEGIN*/ WHERE ";
            $began = true;
        }
        for ($i = 0; $i < $dmd->getPropertyTypeSize(); ++$i) {
            $pt = $dmd->getPropertyType($i);
            $aliasName = $pt->getColumnName();
            if (!$this->beanMetaData_->hasPropertyTypeByAliasName($aliasName)) {
                continue;
            }
            if (!$this->beanMetaData_->getPropertyTypeByAliasName($aliasName)->isPersistent()) {
                continue;
            }
            $columnName = $this->beanMetaData_->convertFullColumnName($aliasName);
            $propertyName = "dto." . $pt->getPropertyName();
            $buf .= "/*IF ";
            $buf .= $propertyName;
            $buf .= " != null*/";
            $buf .= " ";
            if (!$began || $i != 0) {
                $buf .= "AND ";
            }
            $buf .= $columnName;
            $buf .= " = /*";
            $buf .= $propertyName;
            $buf .= "*/null";
            $buf .= "/*END*/";
        }
        if ($began) {
            $buf .= "/*END*/";
        }
        return $buf;
    }

    protected function createAutoSelectSql($argNames) {
        $sql = $this->dbms_->getAutoSelectSql($this->getBeanMetaData());
        $buf = $sql;

    	if (count($argNames) != 0) {
            $began = false;
            if (!(strrpos($sql, "WHERE") > 0)) {
                $buf .= "/*BEGIN*/ WHERE ";
                $began = true;
            }
            foreach($argNames as $key => $argName){
                $columnName = $this->beanMetaData_->convertFullColumnName($argName);
                $buf .= "/*IF ";
                $buf .= $argName;
                $buf .= " != null*/";
                $buf .= " ";
                if (!$began || $key != 0) {
                    $buf .= "AND ";
                }
                $buf .= $columnName;
                $buf .= " = /*";
                $buf .= $argName;
                $buf .= "*/null";
                $buf .= "/*END*/";
            }
            if ($began) {
                $buf .= "/*END*/";
            }
        }
        return $buf;
    }

    protected function checkAutoUpdateMethod(ReflectionMethod $method) {
        $types = $method->getParameters();
        if ( count($types) != 1
                || !$this->isBeanClassAssignable($types[0])
                && !$types[0] instanceof S2Dao_ArrayList
                && !is_array($types) ) {
            throw new S2Dao_IllegalSignatureRuntimeException("EDAO0006", (string)$method);
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

    protected function isSelectArray($methodName){
        $match = null;
        if( preg_match(self::SELECT_ARRAY_NAME, $methodName, $match) ){
            return true;
        } else {
            return false;
        }
    }

    protected function isSelectList($methodName){
        $match = null;
        if( preg_match(self::SELECT_LIST_NAME, $methodName, $match) ){
            return true;
        } else {
            return false;
        }
    }

    protected function isInsert($methodName) {
        foreach ( self::$INSERT_NAMES as $insertNames ){
            if (strcmp($methodName, $insertNames) == 0) {
                return true;
            }
        }
        return false;
    }

    protected function isUpdate($methodName) {
        foreach ( self::$UPDATE_NAMES as $updateNames ){
            // startswith...
            if (strcmp($methodName, $updateNames) == 0) {
                return true;
            }
        }
        return false;
    }

    protected function isDelete($methodName) {
        foreach ( self::$DELETE_NAMES as $deleteNames ) {
            if (strcmp($methodName, $deleteNames) == 0) {
                return true;
            }
        }
        return false;
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
            throw new S2Container_MethodNotFoundRuntimeException($this->daoClass_, $methodName, null);
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

    public static function getDaoInterface($clazz) {
        if ($clazz->isInterface()) {
            return $clazz;
        }
        foreach( $clazz->getInterfaces() as $interface ){
            for($i = 0; $i < count($interface); ++$i){
                $intf = $interface[$i];
                if( ereg("Dao$", $intf->getName()) ) {
                    return $intf;
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