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
class S2Dao_ColumnTypes {
    
    const STRING = 0;
    const OBJECT = 9;

    public static function getColumnType($value = null) {
        if ($value === null) {
            return new S2Dao_ObjectType();
        } else {
            self::getColumnType(new ReflectionClass($value));
        }
        if($value instanceof ReflectionClass){
            $columnType = self::getColumnType0($value);
            if ($columnType != null) {
                return $columnType;
            }
        }
        return new S2Dao_ObjectType();
    }

    private static function getColumnType0(ReflectionClass $clazz) {
        return $clazz->getName();
    }
}

?>