<?php

/**
 * @author nowel
 */
interface S2Dao_DataColumn {

    public function getColumnName();
    
    public function getColumnIndex();
    
    public function getColumnType();
    
    public function setColumnType(S2Dao_ColumnType $columnType);
    
    public function isPrimaryKey();
    
    public function setPrimaryKey($primaryKey);
    
    public function isWritable();
    
    public function setWritable($writable);
    
    public function getFormatPattern();
    
    public function setFormatPattern($formatPattern);
    
    public function convert($value);
}

?>