<?php

/**
 * @author nowel
 */
class S2Dao_MandatoryInterceptor extends S2Dao_AbstractTxInterceptor {

    public function __construct(S2Container_DataSource $datasource) {
        parent::__construct($datasource);
    }

    public function invoke(S2Container_MethodInvocation $invocation) {
        if (!$this->hasTransaction()) {
            throw new S2Dao_SIllegalStateException('ESSR0311', array());
        }
        return $invocation->proceed();
    }
}

?>