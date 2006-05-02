<?php

/**
 * @author nowel
 */
interface S2Dao_DataSet {
    public function getTableSize();
    public function getTableName($table);
    public function getTable($table);
    public function addTable($table);
    public function removeTable($table);
}

?>