<?php

/**
 * @author nowel
 */
class NotSingleRowUpdatedRuntimeException extends UpdateFailureRuntimeException {

    public function __construct($bean, $rows) {
        parent::__construct($bean, $rows);
    }

}
?>
