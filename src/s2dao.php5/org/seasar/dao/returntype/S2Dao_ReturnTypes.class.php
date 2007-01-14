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
class S2Dao_ReturnTypes {
    
    private static $types = array(
        S2Dao_ReturnType::Type_Object => 'S2Dao_ObjectReturnType',
        S2Dao_ReturnType::Type_Array => 'S2Dao_ArrayReturnType',
        S2Dao_ReturnType::Type_List => 'S2Dao_ListReturnType',
        S2Dao_ReturnType::Type_Yaml => 'S2Dao_YamlReturnType',
        S2Dao_ReturnType::Type_Json => 'S2Dao_JsonReturnType',
        S2Dao_ReturnType::Type_Map => 'S2Dao_MapReturnType',
        S2Dao_ReturnType::Type_Xml => 'S2Dao_XmlReturnType',
    );
    private static $instance = array();
    
    private function __construct(){
    }
    
    /**
     * @return S2Dao_ReturnType
     */
    public static function getReturnType($key){
        if(isset(self::$instance[$key])){
            return self::$instance[$key];
        }
        if(isset(self::$types[$key])){
            $type = self::$types[$key];
            $inst = self::$instance[$key] = new $type;
            return $inst;
        }
        return null;
    }
    
    public static function addReturnType($key, S2Dao_ReturnType $handler){
        self::$instance[$key] = $handler;
    }
}

?>