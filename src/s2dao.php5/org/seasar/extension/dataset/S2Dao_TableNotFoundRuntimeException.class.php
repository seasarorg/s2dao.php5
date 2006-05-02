<?php

/**
 * @author nowel
 */
class S2Dao_TableNotFoundRuntimeException extends S2Container_S2RuntimeException {

    private $tableName_;
    
    public function __construct($tableName) {
        parent::__construct('ESSR0067', array($tableName));
        $this->tableName_ = $tableName;
    }
    
    public function getTableName() {
        return $this->tableName_;
    }
}

?>