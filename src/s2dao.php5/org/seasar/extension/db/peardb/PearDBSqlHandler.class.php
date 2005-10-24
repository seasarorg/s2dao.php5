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
class PearDBSqlHandler implements SqlHandler {
	private $log_;

	public function PearDBSqlHandler(){
		$this->log_ = S2Logger::getLogger(get_class($this));
	}

	public function execute($sql,
	                          DataSource $dataSource,
	                          ResultSetHandler $resultSetHandler) {
        $db = $dataSource->getConnection();
        
        $db->setFetchMode(DB_FETCHMODE_ASSOC);
        $result = $db->query($sql); 
        if(DB::isError($result)){
        	$this->log_->error($result->getMessage(),__METHOD__);
        	$this->log_->error($result->getDebugInfo(),__METHOD__);
        	$db->disconnect();
        	exit;
        }
        
        if($result == DB_OK){
        	return $db->affectedRows();
        }
        
        $ret = array();
        while($row = $result->fetchRow()){
            array_push($ret,$resultSetHandler->handle($row));
        }
        $result->free();
        $db->disconnect();
        return $ret;
 	}
}
?>
