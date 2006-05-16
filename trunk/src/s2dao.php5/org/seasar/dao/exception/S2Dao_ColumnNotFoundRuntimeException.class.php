<?php

/**
 * @author nowel
 */
final class S2Dao_ColumnNotFoundRuntimeException extends S2Container_S2RuntimeException {

    private $tableName;
    private $columnName;
    
    public function __construct($tableName, $columnName) {
        parent::__construct('ESSR0068', array($tableName, $columnName));
        $this->tableName = $tableName;
        $this->columnName = $columnName;
    }
    
    public function getTableName() {
        return $this->tableName;
    }
    
    public function getColumnName() {
        return $this->columnName;
    }
}
?>
