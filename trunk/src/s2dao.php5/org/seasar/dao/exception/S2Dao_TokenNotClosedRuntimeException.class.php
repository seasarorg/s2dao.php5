<?php

/**
 * @author nowel
 */
class S2Dao_TokenNotClosedRuntimeException extends S2Container_S2RuntimeException {

    private $token;
    private $sql;
    
    public function __construct($token, $sql) {
        parent::__construct('EDAO0002', array($token, $sql));
        $this->token = $token;
        $this->sql = $sql;
    }
    
    public function getToken() {
        return $this->token;
    }

    public function getSql() {
        return $this->sql;
    }
}
?>
