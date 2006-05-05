<?php

/**
 * @author nowel
 */
class S2Dao_PostgreSQL extends S2Dao_Standard {

    public function getSuffix() {
        return '_pgsql';
    }
    
    public function getSequenceNextValString($sequenceName) {
        return 'SELECT nextval (' . $sequenceName . ')';
    }
    
    // thx blogrammer
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
        return 'SELECT format_type(p.prorettype, NULL) AS ArgsTypes,' .
               ' oidvectortypes(p.proargtypes) AS ResultType, ' .
               ' p.proargnames as ArgsNames ' .
               'FROM pg_proc p, pg_namespace ns' .
               ' WHERE ns.oid = p.pronamespace' .
               ' AND ns.nspname = ' . self::BIND_SCHEME .
               ' AND p.proname = ' . self::BIND_NAME;
    }
}
?>
