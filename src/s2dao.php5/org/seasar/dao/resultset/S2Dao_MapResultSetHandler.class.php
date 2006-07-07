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
class S2Dao_MapResultSetHandler implements S2Dao_ResultSetHandler {
    
    public function __construct() {
    }
    
    public function handle($rs) {
        $returnValue = array();
        while($rows = $rs->fetch(PDO::FETCH_ASSOC)){
            $lower = array_change_key_case($rows, CASE_LOWE);
            $upper = array_change_key_case($rows, CASE_UPPER);
            $returnValue[] = array_merge($lower, $upper, $rows);
        }
        return $returnValue;
    }
}

?>