<?php

/**
 * @author nowel
 */
interface S2Dao_EntityManager {

    public function find($query, $arg1 = null, $arg2 = null, $arg3 = null);
    
    public function findArray($query, $arg1 = null, $arg2 = null, $arg3 = null);
    
    public function findBean($query, $arg1 = null, $arg2 = null, $arg3 = null);
    
    public function findObject($query, $arg1 = null, $arg2 = null, $arg3 = null);
}
?>
