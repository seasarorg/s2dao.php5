<?php

/**
 * @author nowel
 */
interface S2Dao_DataTable {

    public function getTableName();
    
    public function setTableName($tableName);
    
    public function getRowSize();
    
    public function getRow($index);
    
    public function addRow();
    
    public function getRemovedRowSize();
    
    public function getRemovedRow($index);

    public function removeRows();
    
    public function getColumnSize();
    
    public function getColumn($column);
    
    public function hasColumn($columnName);
    
    public function getColumnName($index);
    
    public function getColumnType($column);
    
    public function addColumn($columnName, S2Dao_ColumnType $columnType);

    public function hasMetaData();
    
    public function setupMetaData(PDO $pdo);
    
    public function setupColumns(ReflectionClass $beanClass);
    
    public function copyFrom($source);
}
?>