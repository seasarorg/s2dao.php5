<?php

/**
 * @author Yusuke Hata  
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
