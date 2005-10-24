<?php

/**
 * @author Yusuke Hata
 */
class TokenNotClosedRuntimeException extends S2RuntimeException {

    private $token_;
    private $sql_;
    
    public function __construct($token, $sql) {
        parent::__construct("EDAO0002", array($token, $sql));
        $this->token_ = $token;
        $this->sql_ = $sql;
    }
    
    public function getToken() {
        return $this->token_;
    }

    public function getSql() {
        return $this->sql_;
    }
}
?>
