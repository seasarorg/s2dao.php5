<?php

/**
 * @author nowel
 */
interface S2Dao_ColumnType {
    
    public function convert($value, $formatPattern);
    
    public function equals($arg1, $arg2);
    
    public function getType();
}

?>