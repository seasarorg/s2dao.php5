<?php

/**
 * @author nowel
 */
class S2Dao_SIllegalStateException extends S2Container_S2RuntimeException {

    private $messageCode;
    private $args = array();

    public function __construct($messageCode, array $args) {
        parent::__construct($messageCode, $args);
        $this->messageCode = $messageCode;
        $this->args = $args;
    }

    public function getMessageCode() {
        return $this->messageCode;
    }

    public function getArgs() {
        return $this->args;
    }
}
?>