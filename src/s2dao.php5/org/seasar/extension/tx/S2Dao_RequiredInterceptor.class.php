<?php

/**
 * @author nowel
 */
class S2Dao_RequiredInterceptor extends S2Dao_AbstractTxInterceptor {
    
    public function __construct(S2Container_DataSource $datasource) {
        parent::__construct($datasource);
    }

    public function invoke(S2Container_MethodInvocation $invocation) {
        $began = false;
        
        if (!$this->hasTransaction()) {
            $this->begin();
            $began = true;
        } 
        $ret = null;
        try {
            $ret = $invocation->proceed();
            if ($began) {
                $this->commit();
            }
            return $ret;
        } catch (Exception $e) {
            if ($began) {
                $this->complete($e);
            }
            throw $e;
        }
    }
}
?>