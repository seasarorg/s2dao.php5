<?php

/**
 * @author nowel
 */
interface S2Dao_DataRow {

    public function getValue($value);

    public function setValue($index, $value);
        
    public function remove();
        
    public function getTable();
    
    public function getState();
    
    public function setState(S2Dao_RowState $rowState);
    
    public function copyFrom($source);
}

?>