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
 * @package org.seasar.s2dao.dbms
 */
class S2Dao_PostgreSQL extends S2Dao_Standard {

    public function getSuffix() {
        return '_pgsql';
    }
    
    public function getSequenceNextValString($sequenceName) {
        return 'SELECT nextval (\'' . $sequenceName . '\')';
    }
    
    public function getIdentitySelectString() {
        // ? 'SELECT version()'
        return '';
    }
    
    public function getTableSql(){
        return 'SELECT c.relname AS NAME ' .
               'FROM pg_class c, pg_user u ' .
               'WHERE c.relowner = u.usesysid ' .
               'AND c.relkind = \'r\' ' .
               'AND c.relname !~ \'^(pg_|sql_)\'';
    }

    public function getTableInfoSql(){
        return 'SELECT * FROM ' . self::BIND_TABLE . ' LIMIT 0';
    }

    public function getPrimaryKeySql(){
        return 'SELECT a.attname AS PKEY ' .
               'FROM pg_attribute a, pg_constraint c, pg_class r ' .
               'WHERE c.conrelid = r.oid ' .
               'AND a.attrelid = r.oid AND a.attnum = any (c.conkey) ' .
               'AND r.relname = ' . self::BIND_TABLE . ' AND c.contype = \'p\'';
    }
    
    public function getProcedureNamesSql(){
        return 'SELECT p.proname, ns.nspname AS Scheme' .
               ' FROM pg_proc p LEFT JOIN pg_namespace ns ON ns.oid = p.pronamespace' .
               ' WHERE p.proname = lower(' . self::BIND_NAME . ')' .
               ' AND ns.nspname LIKE lower(' . self::BIND_SCHEME . ')';
    }
    
    public function getProcedureInfoSql(){
        return 'SELECT format_type(p.prorettype, NULL) AS ResultTypes,' .
               ' oidvectortypes(p.proargtypes) AS ArgsTypes, ' .
               ' p.proargnames as ArgsNames ' .
               'FROM pg_proc p, pg_namespace ns' .
               ' WHERE ns.oid = p.pronamespace' .
               ' AND ns.nspname = ' . self::BIND_SCHEME .
               ' AND p.proname = ' . self::BIND_NAME;
    }
    
    public function getLimitOffsetSql(){
        return 'OFFSET ? LIMIT ?';
    }

    public function usableLimitOffsetQuery() {
        return true;
    }
}
?>
