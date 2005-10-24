<?php

/**
 * @author Yusuke Hata
 */
class IfConditionNotFoundRuntimeException extends S2RuntimeException {
    
    public function __construct() {
        parent::__construct("EDAO0004");
    }
}
?>
