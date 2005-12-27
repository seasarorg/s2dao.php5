<?php

/**
 * @author nowel
 */
class S2Dao_PostgreSQL extends S2Dao_Standard {

    public function getSuffix() {
        return "_pgsql";
    }
    
    public function getSequenceNextValString($sequenceName) {
        return "select nextval ('" + $sequenceName +"')";
    }
    
    public function getTableSql(){
        return "SELECT c.relname AS NAME ".
               "FROM pg_class c, pg_user u " .
               "WHERE c.relowner = u.usesysid " .
               "AND c.relkind = 'r' " .
               "AND c.relname !~ '^(pg_|sql_)'";
    }

    public function getTableInfoSql(){
        return "SELECT * FROM " . self::BIND_TABLE . " LIMIT 0";
    }

    public function getPrimaryKeySql(){
        return "SELECT a.attname AS PKEY " .
               "FROM pg_attribute a, pg_constraint c, pg_class r " .
               "WHERE c.conrelid = r.oid " .
               "AND a.attrelid = r.oid AND a.attnum = any (c.conkey) " .
               "AND r.relname = " . self::BIND_TABLE . " AND c.contype = 'p'";
    }
}
?>
