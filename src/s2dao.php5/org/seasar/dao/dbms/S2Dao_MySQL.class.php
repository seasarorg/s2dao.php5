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
class S2Dao_MySQL extends S2Dao_Standard {

    public function getSuffix() {
        return '_mysql';
    }
    
    public function getIdentitySelectString() {
        return 'SELECT LAST_INSERT_ID()';
    }
    
    public function getTableSql(){
        return 'SHOW TABLES';
    }
    
    public function getTableInfoSql(){
        return 'SELECT * FROM ' . self::BIND_TABLE . ' LIMIT 0';
    }
    
    public function getProcedureNamesSql(){
        return 'SELECT p.name, p.db FROM mysql.proc p WHERE p.db LIKE ' . self::BIND_DB .
               ' AND p.name = ' . self::BIND_NAME;
    }
    
    public function getProcedureInfoSql(){
        return 'SELECT param_list, returns FROM mysql.proc' .
               ' WHERE db = ' . self::BIND_DB . 
               ' AND name = ' . self::BIND_NAME;
    }
    
    public function getLimitOffsetSql(){
        return 'LIMIT ?,?';
    }

}
?>
