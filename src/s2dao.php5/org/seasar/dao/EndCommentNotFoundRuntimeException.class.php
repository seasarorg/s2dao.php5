<?php

/**
 * @author Yusuke Hata
 */
class EndCommentNotFoundRuntimeException extends S2RuntimeException {
    
    public function __construct() {
        parent::__construct("EDAO0007");
    }
}
?>
