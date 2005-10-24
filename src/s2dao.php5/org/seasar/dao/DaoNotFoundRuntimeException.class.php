<?php

/**
 * @author Yusuke Hata
 */
class DaoNotFoundRuntimeException extends S2RuntimeException {
    
    private $targetClass_ = null;
    
    public function __construct($targetClass) {
        parent::__construct("EDAO0008", array($targetClass->getName()));
        $this->targetClass_ = $targetClass;
    }
    
    public function getTargetClass() {
        return $this->targetClass_;
    }
}
?>
