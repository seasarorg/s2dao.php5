<?php

/**
 * @author nowel
 */
class S2Dao_NotExactlyOneRowUpdatedRuntimeException extends S2Container_S2RuntimeException {

    public function __construct($rows) {
        parent::__construct('EDAO0016', array($rows));
    }

}
?>