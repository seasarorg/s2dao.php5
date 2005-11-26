<?php

/**
 * @author nowel
 */
class S2Dao_UpdateFailureRuntimeException extends S2Container_S2RuntimeException {

    private $bean_;
    private $rows_;

    public function __construct($bean, $rows) {
        parent::__construct("EDAO0005", array((string)$bean, (string)$rows));
        $this->bean_ = $bean;
        $this->rows_ = $rows;
    }

    public function getBean() {
        return $this->bean_;
    }
    
    public function getRows() {
        return $this->rows_;
    }
}
?>
