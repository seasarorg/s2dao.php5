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
final class S2Dao_PDOType {
    
    const Boolean = PDO::PARAM_BOOL;
    const Integer = PDO::PARAM_INT;
    const Double = PDO::PARAM_INT;
    const String = PDO::PARAM_STR;
    const Object = PDO::PARAM_STMT;
    const Resource = PDO::PARAM_LOB;
    const Null = PDO::PARAM_NULL;
    const Unkwown = PDO::PARAM_STMT;
    
    public static function gettype($phpType = null){
        if($phpType === null){
            return self::Null;
        }
        
        switch($phpType){
            case S2Dao_PHPType::String:
                return self::String;
            case S2Dao_PHPType::Integer:
            case S2Dao_PHPType::Double;
                return self::Integer;
            case S2Dao_PHPType::Boolean:
                return self::Boolean;
            case S2Dao_PHPType::Null:
                return self::Null;
            case S2Dao_PHPType::Resource:
                return self::Resource;
            default:
            case S2Dao_PHPType::Object:
                return self::Object;
        }
    }

}
?>