<?php

/**
 * @author nowel
 */
class S2Dao_IfConditionNotFoundRuntimeException extends S2Container_S2RuntimeException {
    public function __construct() {
        parent::__construct('EDAO0004');
    }
}
?>
