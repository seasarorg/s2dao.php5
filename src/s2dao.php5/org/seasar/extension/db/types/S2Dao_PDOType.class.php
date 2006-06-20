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
final class S2Dao_PDOType {
    
    public static function gettype($phpType = null){
        if($phpType === null){
            return PDO::PARAM_NULL;
        }
        
        switch($phpType){
            case S2Dao_PHPType::String:
                return PDO::PARAM_STR;
            case S2Dao_PHPType::Integer:
            case S2Dao_PHPType::Double;
                return PDO::PARAM_INT;
            case S2Dao_PHPType::Boolean:
                return PDO::PARAM_BOOL;
            case S2Dao_PHPType::Null:
                return PDO::PARAM_NULL;
            case S2Dao_PHPType::Resource:
                return PDO::PARAM_LOB;
            default:
            case S2Dao_PHPType::Object:
                return PDO::PARAM_STMT;
        }
    }

}
?>