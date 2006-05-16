<?php

/**
 * @author nowel
 */
class S2Dao_IllegalSignatureRuntimeException extends S2Container_S2RuntimeException {

    private $signature;
    
    public function __construct($messageCode, $signature) {
        parent::__construct($messageCode, (array)$signature);
        $this->signature = $signature;
    }
    
    public function getSignature() {
        return $this->signature;
    }
}
?>
