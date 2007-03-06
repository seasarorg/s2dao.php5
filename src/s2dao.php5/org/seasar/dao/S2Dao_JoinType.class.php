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
// $Id: $
//
/**
 * @author nowel
 * @package org.seasar.s2dao
 */
class S2Dao_JoinType {
    
    public static $INNER_JOIN = null;
    public static $OUTER_JOIN = null;
    
    private $type = 0;

    private static function staticConst(){
        self::$INNER_JOIN = new S2Dao_JoinType(1);
        self::$OUTER_JOIN = new S2Dao_JoinType(2);
    }
    
    private function __construct($type) {
        $this->type = $type;
    }
    
    /**
     * @return integer
     */
    public function hashCode() {
        return $this->type;
    }
    
    /**
     * @return boolean
     */
    public function equals($obj) {
        if (!($obj instanceof S2Dao_JoinType)) {
            return false;
        }
        return $obj->type == $this->type;
    }
}

?>
