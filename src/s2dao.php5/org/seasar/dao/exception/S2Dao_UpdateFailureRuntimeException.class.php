<?php

/**
 * @author nowel
 */
class S2Dao_UpdateFailureRuntimeException extends S2Container_S2RuntimeException {

    private $bean;
    private $rows;

    public function __construct($bean, $rows) {
        parent::__construct('EDAO0005', array((string)$bean, (string)$rows));
        $this->bean = $bean;
        $this->rows = $rows;
    }

    public function getBean() {
        return $this->bean;
    }
    
    public function getRows() {
        return $this->rows;
    }
}
?>
