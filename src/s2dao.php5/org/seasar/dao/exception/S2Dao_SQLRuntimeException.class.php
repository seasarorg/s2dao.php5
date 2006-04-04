<?php

/**
 * @author nowel
 */
class S2Dao_SQLRuntimeException extends S2Container_S2RuntimeException {
    public function __construct(Exception $cause) {
        parent::__construct('ESSR0071', array(
                                            $cause->getCode(),
                                            $cause->getMessage(),
                                            $cause
                                        )
                                    );
    }
}

?>