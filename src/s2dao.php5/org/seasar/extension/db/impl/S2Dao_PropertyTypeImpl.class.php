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
class S2Dao_PropertyTypeImpl implements S2Dao_PropertyType {

    private $propertyDesc_ = null;
    private $propertyName_ = null;
    private $columnName_ = null;
    private $valueType_ = null;
    private $primaryKey_ = false;
    private $persistent_ = true;

    public function __construct($propertyDesc, $valueType = null, $columnName = null) {
        if($propertyDesc instanceof S2Container_PropertyDesc){
            $this->propertyDesc_ = $propertyDesc;
            $this->propertyName_ = $propertyDesc->getPropertyName();
            $this->columnName_ = $propertyDesc->getPropertyName();
            $this->valueType = gettype($propertyDesc->getPropertyType());
        } else if(is_string($propertyDesc)){
            $this->propertyName_ = $propertyDesc;
            $this->columnName_ = $propertyDesc;
        }

        if($valueType !== null){
            $this->valueType_ = $valueType;
        }
        if($columnName !== null){
            $this->columnName_ = $columnName;
        }
    }

    public function getPropertyDesc() {
        return $this->propertyDesc_;
    }

    public function getPropertyName() {
        return $this->propertyName_;
    }

    public function getColumnName() {
        return $this->columnName_;
    }
    
    public function setColumnName($columnName) {
        $this->columnName_ = $columnName;
    }

    public function getValueType() {
        return $this->valueType_;
    }

    public function setValueType($type) {
        $this->valueType_ = $type;
    }

    public function isPrimaryKey() {
        return $this->primaryKey_;
    }

    public function setPrimaryKey($primaryKey) {
        $this->primaryKey_ = $primaryKey;
    }

    public function isPersistent() {
        return $this->persistent_;
    }

    public function setPersistent($persistent) {
        $this->persistent_ = $persistent;
    }
}
?>
