<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id: $
//
/**
 * @author nowel
 * @package org.seasar.extension.tx
 */
abstract class S2Dao_AbstractTxInterceptor implements S2Container_MethodInterceptor {

    private $connection = null;
    private $txRules = null;
    private $begin = false;

    public function __construct(S2Container_DataSource $datasource) {
        $this->connection = $datasource->getConnection();
        $this->txRules = new S2Dao_ArrayList();
    }

    public final function getConnection() {
        return $this->connection;
    }

    public final function hasTransaction() {
        if($this->begin){
            return $this->begin;
        }
        try {
            $this->connection->beginTransaction();
        } catch(Exception $e){
            $this->begin = true;
            return true;
        }
        return false;
    }

    public final function begin() {
        try {
            if(!$this->begin){
                $this->connection->beginTransaction();
            }
        } catch(Exception $e){
            // TODO: Nest transaction
        }
    }

    public final function commit() {
        try {
            return $this->connection->commit();
        } catch(Exception $e){
            throw $e;
        }
    }

    public final function rollback() {
        try {
            if ($this->hasTransaction()) {
                return $this->connection->rollback();
            }
        } catch(Exception $e){
            throw $e;
        }
    }

    public final function suspend() {
        return $this->connection;
    }

    public final function resume(PDO $connection) {
        $this->connection = $connection;
    }

    public final function complete(Exception $e) {
        $c = $this->txRules->size();
        for ($i = 0; $i < $c; ++$i) {
            $rule = $this->txRules->get($i);
            try {
                if ($rule->isAssignableFrom($e)) {
                    return $rule->complete();
                }
            } catch(Exception $ex){
                throw $ex;
            }
        }
        $this->rollback();
        return false;
    }

    /**
     * @throws Exception
     */
    public final function addCommitRule($class) {
        if($class instanceof Exception){
            $this->txRules->add(new S2Dao_TxRule($this, $class, true));
        } else {
            throw new Exception();
        }
    }

    /**
     * @throws Exception
     */
    public final function addRollbackRule($class) {
        if($class instanceof Exception){
            $this->txRules->add(new S2Dao_TxRule($this, $class, false));
        } else {
            throw new Exception();
        }
    }
}
?>