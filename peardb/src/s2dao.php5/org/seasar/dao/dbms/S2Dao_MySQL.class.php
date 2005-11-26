<?php

/**
 * @author nowel
 */
class S2Dao_MySQL extends S2Dao_Standard {

    public function getSuffix() {
        return "_mysql";
    }
    
    public function getIdentitySelectString() {
        return "SELECT LAST_INSERT_ID()";
    }
}
?>
