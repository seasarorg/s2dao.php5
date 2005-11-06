<?php

/**
 * @author nowel
 */
interface StatementFactory {

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
