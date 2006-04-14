<?php

/**
 * @author nowel
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
        // FIXME
        if($this->begin){
            return $this->begin;
        }
        if($this->connection->getAttribute(PDO::ATTR_AUTOCOMMIT)){
            $this->begin = true;
            return true;
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
            // FIXME
            if(!$this->begin){
                $this->connection->beginTransaction();
            }
        } catch(Exception $e){
            throw $e;
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

    public final function addCommitRule(Exception $exceptionClass) {
        $this->txRules->add(new S2Dao_TxRule($this, $exceptionClass, true));
    }

    public final function addRollbackRule(Exception $exceptionClass) {
        $this->txRules->add(new S2Dao_TxRule($this, $exceptionClass, false));
    }
}
?>