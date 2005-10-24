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
class PearDBDataSource extends AbstractDataSource {
	private $log_;
    protected $type ="";
    protected $dsn ="";
        
    public function PearDBDataSource(){
		$this->log_ = S2Logger::getLogger(get_class($this));
    }
    
    public function setType($type){
        $this->type = $type;	
    }

    public function setDsn($dsn){
        $this->dsn = $dsn;	
    }

    public function getConnection(){
    	if($this->dsn == ""){
            $this->dsn = $this->constructDsn();
    	}

    	$db = DB::connect($this->dsn);
    	/*
    	if(DB::isError($db)){
    		$this->log_->error($db->getMessage(),__METHOD__);
    		$this->log_->error($db->getDebugInfo(),__METHOD__);
    		throw new Exception();
    	}
        */

    	return $db;
    }

    public function disconnect($connection){
    	$ret = $connection->disconnect();
    	if(DB::isError($ret)){
    		$this->log_->error($ret->getMessage(),__METHOD__);
    		$this->log_->error($ret->getDebugInfo(),__METHOD__);
    		throw new Exception();
    	}
    }

    public function __toString(){
    	$str  = 'type = ' . $this->type . ', ';
    	$str .= 'user = ' . $this->user . ', ';
    	$str .= 'password = ' . $this->password . ', ';
    	$str .= 'host = ' . $this->host . ', ';
    	$str .= 'port = ' . $this->port . ', ';
    	$str .= 'database = ' . $this->database . ', ';
    	$str .= 'dsn = ' . $this->dsn;
    	return $str;
    }

    protected function constructDsnArray(){
    	$dsn = array();
        if($this->type != ""){
        	$dsn['phptype'] = $this->type;
        }

        if($this->user != ""){
        	$dsn['username'] = $this->user;
        }
        
        if($this->password != ""){
        	$dsn['password'] = $this->password;
        }

        if($this->host != ""){
        	$hp = $this->host;
            if($this->port != ""){
            	$hp .= ":" . $this->port;
            }
            
            $dsn['hostspec'] = $hp;
        }

        if($this->database != ""){
            $dsn['database'] = $this->database;
        }
        
        return $dsn;
    }

    protected function constructDsn(){
    	$dsn = "";
        if($this->type != ""){
        	$dsn .= $this->type . "://";
        }

        if($this->user != ""){
        	$dsn .= $this->user;
        }
        
        if($this->password != ""){
        	$dsn .= ":" . $this->password;
        }

        if($this->host != ""){
        	$dsn .= "@" . $this->host;
            if($this->port != ""){
            	$dsn .= ":" . $this->port;
            }
        }

        if($this->database != ""){
        	$dsn .= "/" . $this->database;
        }
        
        return $dsn;
    }
}
?>
