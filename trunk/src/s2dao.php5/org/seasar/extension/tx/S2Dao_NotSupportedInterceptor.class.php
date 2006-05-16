<?php

/**
 * @author nowel
 */
class S2Dao_NotSupportedInterceptor extends S2Dao_AbstractTxInterceptor {

    public function __construct(S2Container_DataSource $datasource) {
        parent::__construct($datasource);
    }

    public function invoke(S2Container_MethodInvocation $invocation) {
        $tx = null;
        if ($this->hasTransaction()) {
            $tx = $this->suspend();
        }
        try {
            return $invocation->proceed();
        } catch(Exception $e){
            throw $e;
        }
        
        if ($tx != null) {
            $this->resume($tx);
        }
    }
}
?>