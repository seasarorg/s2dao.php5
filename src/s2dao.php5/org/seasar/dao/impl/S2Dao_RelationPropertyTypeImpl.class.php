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
class S2Dao_RelationPropertyTypeImpl
    extends S2Dao_PropertyTypeImpl
    implements S2Dao_RelationPropertyType {

    protected $relationNo_;
    protected $myKeys_ = array();
    protected $yourKeys_ = array();
    protected $beanMetaData_;

    public function __construct(S2Container_PropertyDesc $propertyDesc,
                                $relationNo = null, $myKeys = null, $yourKeys = null,
                                S2Dao_BeanMetaData $beanMetaData){

        parent::__construct($propertyDesc);
        $this->relationNo_ = $relationNo;
        $this->myKeys_ = $myKeys;
        $this->yourKeys_ = $yourKeys;
        $this->beanMetaData_ = $beanMetaData;
    }

    public function getRelationNo() {
        return $this->relationNo_;
    }

    public function getKeySize() {
        $length = count($this->myKeys_);
        if (0 < $length) {
            return $length;
        } else {
            return $this->beanMetaData_->getPrimaryKeySize();
        }
    }

    public function getMyKey($index) {
        if (0 < count($this->myKeys_)) {
            return $this->myKeys_[$index];
        } else {
            return $this->beanMetaData_->getPrimaryKey($index);
        }
    }

    public function getYourKey($index) {
        if (0 < count($this->yourKeys_)) {
            return $this->yourKeys_[$index];
        } else {
            return $this->beanMetaData_->getPrimaryKey($index);
        }
    }
    
    public function isYourKey($columnName) {
        for ($i = 0; $i < $this->getKeySize(); ++$i) {
            if (strcasecmp($columnName, $this->getYourKey($i)) == 0){
                return true;
            }
        }
        return false;
    }
    
    public function getBeanMetaData() {
        return $this->beanMetaData_;
    }
}
?>
