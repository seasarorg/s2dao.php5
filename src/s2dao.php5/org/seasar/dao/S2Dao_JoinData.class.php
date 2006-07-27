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
class S2Dao_JoinData {
    
    private $joinType;
    private $joinTableName = array();
    private $myKeys = array();
    private $yourKeys = array();
    
    public function __construcy(S2Dao_JoinType $joinType,
                                $joinTableName,
                                array $myKeys,
                                array $yourKeys){
        $this->joinType = $joinType;
        $this->joinTableName = $joinTableName;
        $this->myKeys = $myKeys;
        $this->yourKeys = $yourKeys;
    }
    
    /**
     * @return String
     */
    public function getJoinTableName() {
        return $this->joinTableName;
    }
    
    /**
     * @return JoinType
     */
    public function getJoinType() {
        return $this->joinType;
    }
    
    /**
     * @return array
     */
    public function getMyKeys() {
        return $this->myKeys;
    }
    
    /**
     * @return array
     */
    public function getYourKeys() {
        return $this->yourKeys;
    }
    
    /**
     * @return int
     */
    public function getKeySize() {
        return count($this->myKeys);
    }
    
    /**
     * @return object myKey
     */
    public function getMyKey($j) {
        return $this->myKeys[$j];
    }
    
    /**
     * @return object yourKey
     */
    public function getYourKey($j) {
        return $this->yourKeys[$j];
    }
}

?>