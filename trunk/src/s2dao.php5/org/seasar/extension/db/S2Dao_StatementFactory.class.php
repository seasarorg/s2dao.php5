<?php

/**
 * @author nowel
 */
interface S2Dao_StatementFactory {
    public function createPreparedStatement(PDO $con, $sql);
    public function createCallableStatement(PDO $con, $sql);
}
?>
