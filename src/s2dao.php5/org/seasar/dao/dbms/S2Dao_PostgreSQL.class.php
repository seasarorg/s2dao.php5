<?php

/**
 * @author nowel
 */
class S2Dao_PostgreSQL extends S2Dao_Standard {

    public function getSuffix() {
        return "_postgre";
    }
    
    public function getSequenceNextValString($sequenceName) {
        return "select nextval ('" + $sequenceName +"')";
    }
    
    public function getTableSql(){
        return "SELECT c.relname AS 'Name' " .
               "FROM pg_class c, pg_user u " .
               "WHERE c.relowner = u.usesysid " .
               "    AND c.relkind = 'r' ".
               "    AND NOT EXISTS " .
               "    (SELECT 1 FROM pg_views " .
               "     WHERE viewname = c.relname) " .
               "     AND c.relname !~ '^(pg_|sql_) " .
               "UNION " .
               "SELECT c.relname AS 'Name' " .
               "FROM pg_class c " .
               "WHERE c.relkind = 'r' " .
               "    AND NOT EXISTS " .
               "    (SELECT 1 FROM pg_views " .
               "     WHERE viewname = c.relname) " .
               "     AND NOT EXISTS " .
               "        (SELECT 1 FROM pg_user " .
               "         WHERE usesysid = c.relowner) " .
               "     AND c.relname !~ '^pg_'";
    }

    public function getTableInfoSql(){
        return "SELECT * FROM " . self::BIND_TABLE . " LIMIT 0";
    }
}
?>
