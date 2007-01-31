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
 * @package org.seasar.extension.db.types
 */
class S2Dao_ObjectType implements S2Dao_ValueType {
    
    private static $types = array(
        S2Dao_PHPType::String => PDO::PARAM_STR,
        S2Dao_PHPType::Integer => PDO::PARAM_INT,
        S2Dao_PHPType::Double => PDO::PARAM_INT,
        S2Dao_PHPType::Boolean => PDO::PARAM_BOOL,
        S2Dao_PHPType::Null => PDO::PARAM_NULL,
        S2Dao_PHPType::Resource => PDO::PARAM_LOB,
        S2Dao_PHPType::Object => PDO::PARAM_STR,
    );
    
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
        $type = gettype($value);
        if(isset(self::$types[$type])){
            $stmt->bindValue($index, $value, self::$types[$type]);
        } else {
            $stmt->bindValue($index, $value);
        }
    }
}

?>