<?php

/**
 * @author Yusuke Hata
 */
class PrimaryKeyNotFoundRuntimeException extends S2RuntimeException {

    private $targetClass_;
    
    public function __construct($targetClass) {
        parent::__construct("EDAO0009", (array)$targetClass->getName());
        $this->targetClass_ = $targetClass;
    }
    
    public function getTargetClass() {
        return $this->targetClass_;
    }
}
?>
