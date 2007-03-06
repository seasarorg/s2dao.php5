<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
class S2Dao_PropertyTypeImpl implements S2Dao_PropertyType {

    private $propertyDesc = null;
    private $propertyName = null;
    private $columnName = null;
    private $valueType = null;
    private $primaryKey = false;
    private $persistent = true;

    public function __construct($propertyDesc, $valueType = null, $columnName = null) {
        if($propertyDesc instanceof S2Container_PropertyDesc){
            $this->propertyDesc = $propertyDesc;
            $this->propertyName = $propertyDesc->getPropertyName();
            $this->columnName = $propertyDesc->getPropertyName();
            $this->valueType = gettype($propertyDesc->getPropertyType());
        } else if(is_string($propertyDesc)){
            $this->propertyName = $propertyDesc;
            $this->columnName = $propertyDesc;
        }

        if($valueType !== null){
            $this->valueType = $valueType;
        }
        if($columnName !== null){
            $this->columnName = $columnName;
        }
    }

    public function getPropertyDesc() {
        return $this->propertyDesc;
    }

    public function getPropertyName() {
        return $this->propertyName;
    }

    public function getColumnName() {
        return $this->columnName;
    }
    
    public function setColumnName($columnName) {
        $this->columnName = $columnName;
    }

    public function getValueType() {
        return $this->valueType;
    }

    public function setValueType($type) {
        $this->valueType = $type;
    }

    public function isPrimaryKey() {
        return $this->primaryKey;
    }

    public function setPrimaryKey($primaryKey) {
        $this->primaryKey = $primaryKey;
    }

    public function isPersistent() {
        return $this->persistent;
    }

    public function setPersistent($persistent) {
        $this->persistent = $persistent;
    }
}
?>
