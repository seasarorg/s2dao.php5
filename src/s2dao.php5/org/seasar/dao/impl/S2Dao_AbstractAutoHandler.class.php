<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractAutoHandler extends S2Dao_BasicHandler implements S2Dao_UpdateHandler {

    private static $logger_ = null;
    private $beanMetaData_;
    private $bindVariables_;
    private $bindVariableTypes_;
    private $timestamp_;
    private $versionNo_;
    private $propertyTypes_;
    private $beanCache_ = null;

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null,
                                S2Dao_BeanMetaData $beanMetaData,
                                $propertyTypes) {

        self::$logger_ = S2Container_S2Logger::getLogger(__CLASS__);
        $this->setDataSource($dataSource);
        $this->setStatementFactory($statementFactory);
        $this->beanMetaData_ = $beanMetaData;
        $this->propertyTypes_ = $propertyTypes;
    }

    public function getBeanMetaData() {
        return $this->beanMetaData_;
    }

    protected static function getLogger() {
        return self::$logger_;
    }

    protected function getBindVariables() {
        return $this->bindVariables_;
    }

    protected function setBindVariables($bindVariables) {
        $this->bindVariables_ = $bindVariables;
    }

    protected function getBindVariableTypes() {
        return $this->bindVariableTypes_;
    }

    protected function setBindVariableTypes($types) {
        $this->bindVariableTypes_ = $types;
    }

    protected function getTimestamp() {
        return $this->timestamp_;
    }

    protected function setTimestamp($timestamp) {
        $this->timestamp_ = $timestamp;
    }

    protected function getVersionNo() {
        return $this->versionNo_;
    }

    protected function setVersionNo($versionNo) {
        $this->versionNo_ = $versionNo;
    }

    protected function getPropertyTypes() {
        return $this->propertyTypes_;
    }

    protected function setPropertyTypes($propertyTypes) {
        $this->propertyTypes_ = $propertyTypes;
    }

    public function execute($args, $arg2 = null) {
        if(is_array($args)){
            $bean = $args[0];
            $this->beanCache_ = $bean;
            $this->preUpdateBean($bean);
            $this->setupPropertyTypes($bean);
            $this->setupBindVariables($bean);

            if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
                $this->getLogger()->debug(
                    $this->getCompleteSql($this->bindVariables_)
                );
            }

            return $this->execute($this->getConnection(), false);
        } else if($args instanceof PDO && is_bool($arg2)){
            $ps = $this->prepareStatement($args);
            $ps->setFetchMode(PDO::FETCH_ASSOC);
            return $this->execute($ps, $arg2);
        } else if($args instanceof PDOStatement && is_bool($arg2)){
            try {
                $this->bindArgs($args, $this->bindVariables_, $this->bindVariableTypes_);
                $result = $args->execute();
            } catch(Exception $e){
                throw $e;
            }

            $ret = -1;
            if($result === false){
                $this->getLogger()->error($args->errorCode(), __METHOD__);
                $this->getLogger()->error(print_r($args->errorInfo()), __METHOD__);
                unset($args);
                throw new Exception();
            } else {
                $ret = $args->rowCount();
            }

            $this->postUpdateBean($this->beanCache_);
            return $ret;
        }
    }

    protected function preUpdateBean($bean) {
    }

    protected function postUpdateBean($bean) {
    }

    protected abstract function setupBindVariables($bean);

    protected function setupPropertyTypes($bean){
        $c = count($this->propertyTypes_);
        for($i = 0; $i < $c; $i++){
            $pt = $this->propertyTypes_[$i];
            $value = $pt->getPropertyDesc()->getValue($bean);
            $pt->setValueType(gettype($value));
        }
    }

    protected function setupInsertBindVariables($bean) {
        $varList = new S2Dao_ArrayList();
        $varTypeList = new S2Dao_ArrayList();
        $c = count($this->propertyTypes_);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $this->propertyTypes_[$i];
            if (strcasecmp($pt->getPropertyName(),
                           $this->getBeanMetaData()->getTimestampPropertyName()) == 0) {
                $this->setTimestamp(time());
                $varList->add($this->getTimestamp());
            } else if ($pt->getPropertyName() ===
                       $this->getBeanMetaData()->getVersionNoPropertyName()) {
                $this->setVersionNo(0);
                $varList->add($this->getVersionNo());
            } else {
                $varList->add($pt->getPropertyDesc()->getValue($bean));
            }
            //$varTypeList->add($pt->getPropertyDesc()->getPropertyType());
            $varTypeList->add($pt->getValueType());
        }
        $this->setBindVariables($varList->toArray());
        $this->setBindVariableTypes($varTypeList->toArray());
    }

    protected function setupUpdateBindVariables($bean) {
        $varList = new S2Dao_ArrayList();
        $varTypeList = new S2Dao_ArrayList();

        for ($i = 0; $i < count($this->propertyTypes_); ++$i) {
            $pt = $this->propertyTypes_[$i];
            if (strcasecmp($pt->getPropertyName(),
                    $this->getBeanMetaData()->getTimestampPropertyName()) == 0) {
                $this->setTimestamp(time());
                $varList->add($this->getTimestamp());
            } else if ($pt->getPropertyName() ===
                    $this->getBeanMetaData()->getVersionNoPropertyName()) {
                $value = $pt->getPropertyDesc()->getValue($bean);
                $intValue = IntegerConversionUtil::toPrimitiveInt($value) + 1;
                $this->setVersionNo($intValue);
                $varList->add($this->getVersionNo());
            } else {
                $varList->add($pt->getPropertyDesc()->getValue($bean));
            }
            //$varTypeList->add($pt->getPropertyDesc()->getPropertyType());
            $varTypeList->add($pt->getValueType());
        }
        $this->addAutoUpdateWhereBindVariables($varList, $varTypeList, $bean);
        $this->setBindVariables($varList->toArray());
        $this->setBindVariableTypes($varTypeList->toArray());
    }

    protected function setupDeleteBindVariables($bean) {
        $varList = new S2Dao_ArrayList();
        $varTypeList = new S2Dao_ArrayList();
        $this->addAutoUpdateWhereBindVariables($varList, $varTypeList, $bean);
        $this->setBindVariables($varList->toArray());
        $this->setBindVariableTypes($varTypeList->toArray());
    }

    protected function addAutoUpdateWhereBindVariables($varList, $varTypeList, $bean) {
        $bmd = $this->getBeanMetaData();
        for ($i = 0; $i < $bmd->getPrimaryKeySize(); ++$i) {
            $pt = $bmd->getPropertyTypeByColumnName($bmd->getPrimaryKey($i));
            $pd = $pt->getPropertyDesc();
            $varList->add($pd->getValue($bean));
            //$varTypeList->add($pd->getPropertyType());
            $varTypeList->add($pt->getValueType());
        }
        if ($bmd->hasVersionNoPropertyType()) {
            $pt = $bmd->getVersionNoPropertyType();
            $pd = $pt->getPropertyDesc();
            $varList->add($pd->getValue($bean));
            //$varTypeList->add($pd->getPropertyType());
            $varTypeList->add($pt->getValueType());
        }
        if ($bmd->hasTimestampPropertyType()) {
            $pt = $bmd->getTimestampPropertyType();
            $pd = $pt->getPropertyDesc();
            $varList->add($pd->getValue($bean));
            //$varTypeList->add($pd->getPropertyType());
            $varTypeList->add($pt->getValueType());
        }
    }

    protected function updateTimestampIfNeed($bean) {
        if ($this->getTimestamp() != null) {
            $pd = $this->getBeanMetaData()->getTimestampPropertyType()->getPropertyDesc();
            $pd->setValue($bean, $this->getTimestamp());
        }
    }

    protected function updateVersionNoIfNeed($bean) {
        if ($this->getVersionNo() != null) {
            $pd = $this->getBeanMetaData()->getVersionNoPropertyType()->getPropertyDesc();
            $pd->setValue($bean, $this->getVersionNo());
        }
    }
}
?>
