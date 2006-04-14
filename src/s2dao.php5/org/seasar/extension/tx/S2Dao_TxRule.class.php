<?php

/**
 * @author nowel
 */
final class S2Dao_TxRule {
    
    private $tx = null;
    private $exceptionClass;
    private $commit;

    public function __construct(S2Dao_AbstractTxInterceptor $tx,
                                Exception $exceptionClass, $commit) {
        $this->tx = $tx;
        $this->exceptionClass = $exceptionClass;
        $this->commit = $commit;
    }

    public function isAssignableFrom(Exception $t) {
        return $t instanceof Exception;
    }

    public function complete() {
        if ($this->commit) {
            $this->tx->commit();
        } else {
            $this->tx->rollback();
        }
        return $this->commit;
    }
}
?>