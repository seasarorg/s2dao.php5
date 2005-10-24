<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: klove                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @package org.seasar.extension.db.peardb
 * @author klove
 */
class PearDBTxInterceptor extends AbstractTxInterceptor {
	private $log_;
	
	public function PearDBTxInterceptor(DBSession $session) {
		parent::__construct($session);
		$this->log_ = S2Logger::getLogger(get_class($this));
	}

    function begin(){
    	try{
    	    $this->session->connect();
    	}catch(Exception $e){
    		throw $e;
    	}
    	$ret = $this->session->getConnection()->autoCommit(false);
    	if(DB::isError($ret)){
    	    $this->log_->error($ret->getMessage(),__METHOD__);
    		$this->log_->error($ret->getDebugInfo(),__METHOD__);
        	$this->session->disconnect();
    		throw new Exception();
    	}
    	$this->log_->info("auto commit false. (start transaction.)",__METHOD__);
    }

    function commit(){
    	$ret = $this->session->getConnection()->commit();
    	if(DB::isError($ret)){
    	    $this->log_->error($ret->getMessage(),__METHOD__);
    		$this->log_->error($ret->getDebugInfo(),__METHOD__);
        	$this->session->disconnect();
    		throw new Exception();
    	}
    	
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