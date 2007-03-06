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
class S2Dao_Firebird extends S2Dao_Standard {

    public function getSuffix() {
        return '_firebird';
    }
    
    public function getSequenceNextValString($sequenceName) {
        return 'SELECT GEN_ID( ' . $sequenceName . ', 1 ) from RDB$DATABASE';
    }

    public function getTableSql(){
        return 'SELECT RDB$RELATION_NAME AS NAME ' . 
               'FROM RDB$RELATION_FIELDS WHERE RDB$SYSTEM_FLAG = 0';
    }

    public function getTableInfoSql(){
        return 'SELECT * FROM ' . self::BIND_TABLE . ' WHERE 1 = 1';
    }
    
    public function getPrimaryKeySql(){
        // TODO: PDO bugs first columns is null array
        return 'SELECT b.RDB$FIELD_NAME AS NAME ' .
               'FROM rdb$indices a, rdb$index_segments b, ' . self::BIND_TABLE .' c ' .
               'WHERE a.RDB$INDEX_NAME = b.RDB$INDEX_NAME ' .
               'AND a.RDB$RELATION_NAME LIKE \'' . self::BIND_TABLE . '%\'';
    }
}

?>
