<?php

/**
 * @author nowel
 */
interface S2Dao_ValueType {

    /**
     * 
     */
    public function getValue(array $resultset, $key);
    
    /**
     * 
     */
    public function bindValue(PDOStatement $stmt, $index, $value);
}

?>