<?php

/**
 * @author nowel
 */
class S2Dao_NoRowsUpdatedRuntimeException extends S2Container_S2RuntimeException {

    public function __construct() {
        parent::__construct('EDAO0015');
    }

}
?>