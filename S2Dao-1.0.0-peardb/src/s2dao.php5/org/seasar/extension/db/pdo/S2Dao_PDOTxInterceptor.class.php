<?php

/**
 * @author nowel
 */
class S2Dao_PDOTxInterceptor extends S2Container_AbstractTxInterceptor {
    
    private $log_;
    
    public function __construct(S2Container_DBSession $session) {
        parent::__construct($session);
        $this->log_ = S2Container_S2Logger::getLogger(__CLASS__);
    }

    function begin(){
        
        try {
            $this->session->connect();
            $ret = $this->session->getConnection()->beginTransaction();
        } catch(PDOException $e){
            $this->log_->error($e->getMessage(), __METHOD__);
            $this->log_->error($e->getCode(), __METHOD__);
            $this->session->disconnect();
            throw new Exception();
        }
        $this->log_->info("auto commit false. (start transaction.)",__METHOD__);
    }

    function commit(){
        $ret = $this->session->getConnection()->commit();
        /*
        if(DB::isError($ret)){
            $this->log_->error($ret->getMessage(),__METHOD__);
            $this->log_->error($ret->getDebugInfo(),__METHOD__);
            $this->session == null;
            throw new Exception();
        }
        */
        
        $this->session->disconnect();
        $this->log_->info("transaction commit and disconnect.",__METHOD__);
    }

    function rollback(){
        $this->session->getConnection()->rollback();
        $this->session->disconnect();
        $this->log_->info("transaction rollback and disconnect.",__METHOD__);
    }

    function hasTransaction(){
        return $this->session->hasConnected();
    }
}
?>
