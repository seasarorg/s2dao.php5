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
final class S2Dao_ValueTypes {
    
    public static $types = array(
        S2Dao_PHPType::String => 'S2Dao_StringType',
        S2Dao_PHPType::Integer => 'S2Dao_IntegerType',
        S2Dao_PHPType::Double => 'S2Dao_DoubleType',
        S2Dao_PHPType::Boolean => 'S2Dao_BooleanType',
        S2Dao_PHPType::Null => 'S2Dao_NullType',
        S2Dao_PHPType::Resource => 'S2Dao_LobType',
        S2Dao_PHPType::Object => 'S2Dao_ObjectType'
    );
    
    private static $instance = array();
    
    private function __construct(){
    }
    
    /**
     * @return S2Dao_ValueType
     */
    public static function getValueType($type = null){
        if($type === null){
            /// TODO
            //$type = S2Dao_PHPType::Null;
            $type = S2Dao_PHPType::Object;
        } else if(is_object($type)){
            $type = S2Dao_PHPType::Object;
        }
        if(isset(self::$instance[$type])){
            return self::$instance[$type];
        }
        $class = null;
        if(isset(self::$types[$type])){
            $class = self::$types[$type];
        } else {
            $class = self::$types[S2Dao_PHPType::Object];
        }
        $instance = self::$instance[$type] = new $class;
        return $instance;
    }

}

?>