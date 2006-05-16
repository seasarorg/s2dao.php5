<?php

/**
 * @author nowel
 */
class S2Dao_Firebird extends S2Dao_Standard {

    public function getSuffix() {
        return '_firebird';
    }
    
    public function getSequenceNextValString($sequenceName) {
        return 'SELECT GEN_ID( ' + $sequenceName + ', 1 ) from RDB$DATABASE';
    }

    public function getTableSql(){
        return 'SELECT RDB$RELATION_NAME ' . 
               'FROM RDB$RELATION_FIELDS WHERE RDB$SYSTEM_FLAG = 0';
    }

    public function getTableInfoSql(){
        return 'SELECT * FROM ' . self::BIND_TABLE . ' WHERE 1 = 1';
    }
}

?>
