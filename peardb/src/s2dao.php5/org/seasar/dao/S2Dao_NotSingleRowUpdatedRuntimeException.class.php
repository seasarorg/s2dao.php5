<?php

/**
 * @author nowel
 */
class S2Dao_NotSingleRowUpdatedRuntimeException extends S2Dao_UpdateFailureRuntimeException {
    public function __construct($bean, $rows) {
        parent::__construct($bean, $rows);
    }
}
?>
