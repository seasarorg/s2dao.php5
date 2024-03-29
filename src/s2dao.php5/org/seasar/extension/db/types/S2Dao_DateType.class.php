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
 * @package org.seasar.extension.db.types
 */
class S2Dao_DateType implements S2Dao_ValueType {
    
    const DATE_FORMAT = 'Y-m-d H:i:s';
    
    /**
     * 
     */
    public function getValue(array $resultset, $key){
        return $resultset[$key];
    }
    
    /**
     * 
     */
    public function bindValue(PDOStatement $stmt, $index, $value){
        $bindValue = $value;
        $toTime = strtotime($value);
        if($toTime !== -1){
            $bindValue = date(self::DATE_FORMAT, $toTime);
        } else if(is_long($value)){
            $bindValue = date(self::DATE_FORMAT, $value);
        } else if(empty($value)){
            $bindValue = date(self::DATE_FORMAT, time());
        }
        $stmt->bindValue($index, $bindValue, PDO::PARAM_STR);
    }
}

?>