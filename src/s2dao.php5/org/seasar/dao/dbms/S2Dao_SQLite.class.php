<?php

/**
 * @author nowel
 */
class S2Dao_SQLite extends S2Dao_Standard {

    public function getSuffix() {
        return "_sqlite";
    }
    
    public function getIdentitySelectString() {
        return "SELECT last_insert_rowid()";
    }
}
?>
