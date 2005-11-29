<?php

/**
 * @author nowel
 */
class S2Dao_BasicStatementFactory implements S2Dao_StatementFactory {
    
    //public static final StatementFactory INSTANCE = new BasicStatementFactory();
    
    public function createPreparedStatement(PDO $con, $sql) {
        return $con->prepare($sql);
    }
    
    public function createCallableStatement(PDO $con, $sql) {
        return $con->prepare($sql);
    }
}
?>
