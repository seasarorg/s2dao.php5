<?php

/**
 * @author nowel
 */
class S2Dao_RequiresNewInterceptor extends S2Dao_AbstractTxInterceptor {
    
    public function __construct(S2Container_DataSource $datasource) {
        parent::__construct($datasource);
    }

    public function invoke(S2Container_MethodInvocation $invocation) {
        $tx = null;
        if ($this->hasTransaction()) {
            $tx = $this->suspend();
        }
        $ret = null;
        $this->begin();
        try {
            $ret = $invocation->proceed();
            $this->commit();
        } catch (Exception $e) {
            $this->complete($e);
            throw $e;
        }
        
        if ($tx != null) {
            $this->resume($tx);
        }
        return $ret;
    }
}
?>