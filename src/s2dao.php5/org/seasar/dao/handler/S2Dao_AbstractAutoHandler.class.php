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
// $Id$
//
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
                                S2Dao_StatementFactory $statementFactory,
                                S2Dao_BeanMetaData $beanMetaData,
                                $propertyTypes) {

        self::$logger_ = S2Container_S2Logger::getLogger(get_class($this));
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
            $this->preUpdatePropertyTypes($bean);
            $this->preUpdateBean($bean);
            $this->setupBindVariables($bean);
            $this->setupBindValiablesType($bean);

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
            $this->postUpdatePropertyTypes($this->beanCache_);
            return $ret;
        }
    }

    protected function preUpdateBean($bean) {
    }

    protected function postUpdateBean($bean) {
    }

    protected abstract function setupBindVariables($bean);

    protected function preUpdatePropertyTypes($bean){
        $bmd = $this->getBeanMetaData();
        $c = $bmd->getPropertyTypeSize();
        for($i = 0; $i < $c; $i++){
            $pt = $bmd->getPropertyType($i);
            $pd = $pt->getPropertyDesc();
            $value = $pd->getValue($bean);
            $pt->setValueType(gettype($value));
        }
    }
    
    // TODO
    protected function setupBindValiablesType($bean){
        $c = count($this->bindVariableTypes_);
        for($i = 0; $i < $c; $i++){
            $type =& $this->bindVariableTypes_[$i];
            if($type == gettype(null)){
                $type = gettype($this->bindVariables_[$i]);
            }
        }
    }

    
    protected function postUpdatePropertyTypes($bean){
        $this->preUpdatePropertyTypes($bean);
    }

    protected function setupInsertBindVariables($bean) {
        $varList = new S2Dao_ArrayList();
        $varTypeList = new S2Dao_ArrayList();
        $bmd = $this->getBeanMetaData();
        $timestampPropertyName = $bmd->getTimestampPropertyName();
        $versionNoPropertyName = $bmd->getVersionNoPropertyName();
        
        $c = count($this->propertyTypes_);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $this->propertyTypes_[$i];
            $propName = $pt->getPropertyName();
            if (strcasecmp($propName, $timestampPropertyName) == 0) {
                $this->setTimestamp(time());
                $varList->add($this->getTimestamp());
            } else if (strcmp($propName, $versionNoPropertyName) == 0) {
                $this->setVersionNo(0);
                $varList->add($this->getVersionNo());
            } else {
                $varList->add($pt->getPropertyDesc()->getValue($bean));
            }
            $varTypeList->add($pt->getValueType());
        }
        $this->setBindVariables($varList->toArray());
        $this->setBindVariableTypes($varTypeList->toArray());
    }

    protected function setupUpdateBindVariables($bean) {
        $varList = new S2Dao_ArrayList();
        $varTypeList = new S2Dao_ArrayList();
        $bmd = $this->getBeanMetaData();
        $timestampPropertyName = $bmd->getTimestampPropertyName();
        $versionNoPropertyName = $bmd->getVersionNoPropertyName();

        $c = count($this->propertyTypes_);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $this->propertyTypes_[$i];
            $propName = $pt->getPropertyName();
            if (strcasecmp($propName, $timestampPropertyName) == 0) {
                $this->setTimestamp(date('Y-m-d H:i:s', time()));
                $varList->add($this->getTimestamp());
            } else if (strcmp($propName, $versionNoPropertyName) == 0) {
                $value = $pt->getPropertyDesc()->getValue($bean);
                $intValue = (int)$value + 1;
                $this->setVersionNo($intValue);
                $varList->add($this->getVersionNo());
            } else {
                $varList->add($pt->getPropertyDesc()->getValue($bean));
            }
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

    protected function addAutoUpdateWhereBindVariables(S2Dao_ArrayList $varList,
                                                       S2Dao_ArrayList $varTypeList,
                                                       $bean) {
        $bmd = $this->getBeanMetaData();
        for ($i = 0; $i < $bmd->getPrimaryKeySize(); ++$i) {
            $pt = $bmd->getPropertyTypeByColumnName($bmd->getPrimaryKey($i));
            $varList->add($pt->getPropertyDesc()->getValue($bean));
            $varTypeList->add($pt->getValueType());
        }
        if ($bmd->hasVersionNoPropertyType()) {
            $pt = $bmd->getVersionNoPropertyType();
            $varList->add($pt->getPropertyDesc()->getValue($bean));
            $varTypeList->add($pt->getValueType());
        }
        if ($bmd->hasTimestampPropertyType()) {
            $pt = $bmd->getTimestampPropertyType();
            $varList->add($pt->getPropertyDesc()->getValue($bean));
            $varTypeList->add($pt->getValueType());
        }
    }

    protected function updateTimestampIfNeed($bean) {
        if ($this->getTimestamp() !== null) {
            $pd = $this->getBeanMetaData()->getTimestampPropertyType()->getPropertyDesc();
            $pd->setValue($bean, $this->getTimestamp());
        }
    }

    protected function updateVersionNoIfNeed($bean) {
        if ($this->getVersionNo() !== null) {
            $pd = $this->getBeanMetaData()->getVersionNoPropertyType()->getPropertyDesc();
            $pd->setValue($bean, (int)$this->getVersionNo());
        }
    }
}
?>
