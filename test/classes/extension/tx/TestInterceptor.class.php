<?php

class TestInterceptor extends S2Dao_AbstractTxInterceptor {
    
    public $result;

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
        } catch (Exception $t) {
            if ($began) {
                $result = $this->complete($t);
            }
            throw $t;
        }
    }
}