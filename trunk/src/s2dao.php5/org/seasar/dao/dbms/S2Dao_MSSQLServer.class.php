<?php

/**
 * @author nowel
 */
class S2Dao_MSSQLServer extends S2Dao_Standard {

    public function getSuffix() {
        return '_mssql';
    }
    
    public function getIdentitySelectString() {
        return 'select @@identity';
    }

    public function getTableSql(){
    }

    public function getTableInfoSql(){
    }
}

?>
