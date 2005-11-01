<?php

/**
 * @author nowel
 */
class SQLite extends Standard {

    public function getSuffix() {
        return "_sqlite";
    }
    
    public function getIdentitySelectString() {
        return "SELECT last_insert_rowid()";
    }
}
?>
