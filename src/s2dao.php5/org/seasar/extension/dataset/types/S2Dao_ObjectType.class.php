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
class S2Dao_ObjectType implements S2Dao_ColumnType {

    function __construct() {
    }
    
    public function convert($value, $formatPattern) {
        return $value;
    }
    
    public function equals($arg1, $arg2) {
        if ($arg1 === null) {
            return $arg2 === null;
        }
        return $this->doEquals($arg1, $arg2);
    }
    
    protected function doEquals($arg1, $arg2) {
        try {
            $arg1 = $this->convert($arg1, null);
        } catch (Exception $t) {
            return false;
        }
        try {
            $arg2 = $this->convert($arg2, null);
        } catch (Exception $t) {
            return false;
        }
        return $arg1->equals($arg2);
    }
    
    public function getType() {
        return gettype(get_class($this));
    }
}
?>