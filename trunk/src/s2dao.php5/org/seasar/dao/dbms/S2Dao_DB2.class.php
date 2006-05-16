<?php

/**
 * @author nowel
 */
class S2Dao_DB2 extends S2Dao_Standard {

    public function getSuffix() {
        return '_db2';
    }
    
    public function getIdentitySelectString() {
        return 'values IDENTITY_VAL_LOCAL()';
    }

    public function getSequenceNextValString($sequenceName) {
        return 'values nextval for ' . $sequenceName;
    }

    public function getTableSql(){
    }

    public function getTableInfoSql(){
    }
}
