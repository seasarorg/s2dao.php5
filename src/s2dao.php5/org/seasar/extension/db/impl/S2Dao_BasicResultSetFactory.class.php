<?php

/**
 * @author nowel
 */
class S2Dao_BasicResultSetFactory implements S2Dao_ResultSetFactory {
    
    public function createResultSet($ps) {
        return $ps->execute();
    }
}
?>
