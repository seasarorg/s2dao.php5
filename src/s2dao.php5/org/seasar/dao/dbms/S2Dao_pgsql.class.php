<?php

/**
 * @author nowel
 */
class S2Dao_pgsql extends S2Dao_Standard {

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
               // "AND NOT EXISTS (SELECT 1 FROM pg_views WHERE viewname = c.relname)" .
               "AND c.relname !~ '^(pg_|sql_)'";
    }

    public function getTableInfoSql(){
        return "SELECT * FROM " . self::BIND_TABLE . " LIMIT 0";
    }

    public function getPrimaryKeySql(){
        return "SELECT c2.relname AS NAME, i.indisprimary, ".
               "pg_catalog.pg_get_indexdef(i.indexrelid, 0, true) AS PKEY " .
               "FROM pg_catalog.pg_class c,pg_catalog.pg_class c2, " .
               "     pg_catalog.pg_index i ".
               "WHERE c.oid = i.indrelid AND i.indexrelid = c2.oid ".
               "AND c2.relname ~ ". self::BIND_TABLE;
    }
}
?>
