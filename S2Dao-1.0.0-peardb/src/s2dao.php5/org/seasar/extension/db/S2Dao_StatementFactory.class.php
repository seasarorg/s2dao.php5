<?php

/**
 * @author nowel
 */
interface S2Dao_StatementFactory {

    /**
     * @return PreparedStatement
     */
	public function createPreparedStatement(Connection $con, $sql);
	
    /**
     * @return ClassableStatement
     */
	public function createCallableStatement(Connection $con, $sql);
}
?>
