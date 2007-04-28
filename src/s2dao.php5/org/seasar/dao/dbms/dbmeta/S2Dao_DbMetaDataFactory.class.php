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
 * @package org.seasar.s2dao.dbms.dbmeta
 */
final class S2Dao_DbMetaDataFactory {
    
    const DbMetaData_Suffix = 'DbMetaData';
    
    private static $instance = array();
    
    public static function create(PDO $db, S2Dao_Dbms $dbms){
        $dbmd = get_class($dbms) . self::DbMetaData_Suffix;
        if(isset(self::$instance[$dbmd])){
            return self::$instance[$dbmd];
        }
        // TODO: DbMeta classes mapping table
        if(strcasecmp($dbmd, 'S2Dao_MySQLDbMetaData') === 0){
            $instance = self::$instance[$dbmd] = new S2Dao_StandardDbMetaData($db, $dbms);
            return $instance;
        } else if(class_exists($dbmd)){
            $instance = self::$instance[$dbmd] = new $dbmd($db, $dbms);
            return $instance;
        }
        return new S2Dao_StandardDbMetaData($db, $dbms);
    }
}

?>