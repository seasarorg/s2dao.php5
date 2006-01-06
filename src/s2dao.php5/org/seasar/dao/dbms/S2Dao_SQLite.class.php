<?php

/**
 * @author nowel
 */
class S2Dao_SQLite extends S2Dao_Standard {

    public function getSuffix() {
        return '_sqlite';
    }
    
    public function getIdentitySelectString() {
        return 'SELECT last_insert_rowid()';
    }
    
    public function getTableSql(){
        return 'SELECT name FROM sqlite_master WHERE type=\'table\' ' .
               'UNION ALL ' .
               'SELECT name FROM sqlite_temp_master WHERE type=\'table\' ' .
               'ORDER BY name';
    }
    
    public function getTableInfoSql(){
        return 'SELECT * FROM ' . self::BIND_TABLE . ' LIMIT 1';
    }

    public function getPrimaryKeySql(){
        return 'PRAGMA table_info(' . self::BIND_TABLE . ')';
    }
}
?>
