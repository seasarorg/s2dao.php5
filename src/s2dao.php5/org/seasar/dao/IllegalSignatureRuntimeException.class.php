<?php

/**
 * @author nowel
 */
class IllegalSignatureRuntimeException extends S2RuntimeException {

    private $signature_;
    
    public function __construct($messageCode, $signature) {
        parent::__construct($messageCode, array($signature));
        $this->signature_ = $signature;
    }
    
    public function getSignature() {
        return $this->signature_;
    }
}
?>
