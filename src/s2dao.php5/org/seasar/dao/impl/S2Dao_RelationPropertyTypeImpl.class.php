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
class S2Dao_RelationPropertyTypeImpl
    extends S2Dao_PropertyTypeImpl
    implements S2Dao_RelationPropertyType {

    protected $relationType = RelationType::belongTo;

    protected $relationNo;

    protected $joinTableName;

    protected $myKeys = array();

    protected $yourKeys = array();

    protected $myJoinKeys = array();

    protected $yourJoinKeys = array();

    protected $beanMetaData;

    public function __construct() {
        $args = func_get_args();
        $argNum = func_num_args();
        if($argNum == 1){
            $this->__call('__construct0', $args);
        } else if($argNum == 5){
            $arg1 = func_get_arg(1);
            if(is_integer($arg1) && !is_array($arg1)){
                $this->__call('__construct1', $args);
            }
            if(is_array($arg1) && !is_string($arg1)){
                $this->__call('__construct2', $args);
            }
        } else if($argNum == 8){
            $this->__call('__construct3', $args);
        } else {
            throw new S2Container_IllegalArgumentException(get_class($this));
        }
    }
    
    public function __construct0(S2Container_PropertyDesc $propertyDesc) {
        parent::__construct($propertyDesc);
    }

    public function __construct1(S2Container_PropertyDesc $propertyDesc,
                                 $relationNo,
                                 array $myKeys,
                                 array $yourKeys,
                                 S2Dao_BeanMetaData $beanMetaData) {
        parent::__construct($propertyDesc);
        $this->relationNo = $relationNo;
        $this->myKeys = $myKeys;
        $this->yourKeys = $yourKeys;
        $this->beanMetaData = $beanMetaData;
    }

    public function __construct2(S2Container_PropertyDesc $propertyDesc,
                                 array $myKeys,
                                 array $yourKeys,
                                 S2Dao_BeanMetaData $beanMetaData,
                                 S2Dao_RelationType $relationType) {
        parent::__construct($propertyDesc);
        $this->myKeys = $myKeys;
        $this->yourKeys = $yourKeys;
        $this->beanMetaData = $beanMetaData;
        $this->relationType = $relationType;
    }

    public function __construct3(S2Container_PropertyDesc $propertyDesc,
                                $joinTable,
                                array $myKeys,
                                array $yourKeys,
                                array $myJoinKeys,
                                array $yourJoinKeys,
                                S2Dao_BeanMetaData $beanMetaData,
                                S2Dao_RelationType $relationType) {
        parent::__constuct($propertyDesc);
        $this->joinTableName = $joinTable;
        $this->myKeys = $myKeys;
        $this->yourKeys = $yourKeys;
        $this->myJoinKeys = $myJoinKeys;
        $this->yourJoinKeys = $yourJoinKeys;
        $this->beanMetaData = $beanMetaData;
        $this->relationType = $relationType;
    }
    
    private function __call($name, $args){
        if(method_exists(__CLASS__, $args)){
            return call_user_func_array(array($this, $name), $args);
        }
    }

    /**
     * @return RelationType
     */
    public function getRelationType() {
        return $this->relationType;
    }

    public function getRelationNo() {
        return $this->relationNo;
    }

    public function getJoinTableName() {
        return $this->joinTableName;
    }
    
    public function getYourJoinKeys() {
        return $this->yourJoinKeys;
    }

    public function getYourKeys() {
        return $this->yourKeys;
    }

    /**
     * @see org.seasar.dao.RelationPropertyType#getKeySize()
     */
    public function getKeySize() {
        if (0 < count($this->myKeys)) {
            return count($this->myKeys);
        }
        return $this->beanMetaData->getPrimaryKeySize();
    }

    /**
     * @see org.seasar.dao.RelationPropertyType#getMyKey(int)
     */
    public function getMyKey($index) {
        if (0 < count($this->myKeys)) {
            return $this->myKeys[$index];
        }
        return $this->beanMetaData->getPrimaryKey($index);
    }
    
    public function getMyJoinKey($index) {
        return $this->myJoinKeys[$index];
    }
    
    public function getYourJoinKey($index) {
        return $this->yourJoinKeys[$index];
    }
    
    /**
     * @see org.seasar.dao.RelationPropertyType#getYourKey(int)
     */
    public function getYourKey($index) {
        if (0 < count($this->yourKeys)) {
            return $this->yourKeys[$index];
        }
        return $this->beanMetaData->getPrimaryKey($index);
    }

    /**
     * @see org.seasar.dao.RelationPropertyType#isYourKey(string)
     */
    public function isYourKey($columnName) {
        $size = $this->getKeySize();
        for ($i = 0; $i < $size; ++$i) {
            if (strcasecmp($columnName, $this->getYourKey($i)) == 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @see org.seasar.extension.jdbc.RelationPropertyType#getBeanMetaData()
     * @return BeanMetaData
     */
    public function getBeanMetaData() {
        return $this->beanMetaData;
    }
}

?>
