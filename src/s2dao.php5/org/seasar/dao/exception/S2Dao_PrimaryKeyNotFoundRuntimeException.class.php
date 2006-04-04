<?php

/**
 * @author nowel
 */
class S2Dao_PrimaryKeyNotFoundRuntimeException extends S2Container_S2RuntimeException {

    private $targetClass;
    
    public function __construct($targetClass) {
        parent::__construct('EDAO0009', (array)$targetClass->getName());
        $this->targetClass = $targetClass;
    }
    
    public function getTargetClass() {
        return $this->targetClass;
    }
}
?>
