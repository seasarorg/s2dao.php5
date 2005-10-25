<?php

/**
 * @author nowel
 */
class MySQL extends Standard {

    public function getSuffix() {
        return "_mysql";
    }
    
    public function getIdentitySelectString() {
        return "SELECT LAST_INSERT_ID()";
    }
}
?>
