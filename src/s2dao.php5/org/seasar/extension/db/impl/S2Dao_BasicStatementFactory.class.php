<?php

/**
 * @author nowel
 */
class S2Dao_BasicStatementFactory implements S2Dao_StatementFactory {
	
	public static $INSTANCE = null;
    public static $init = false;
    
    public static function staticConst(){
        self::$INSTANCE = new S2Dao_BasicStatementFactory();
        self::$init = true; 
    }
	
	public function createPreparedStatement(Connection $con, $sql) {
		return S2Dao_ConnectionUtil::prepareStatement($con, $sql);
	}
	
	public function createCallableStatement(Connection $con, $sql) {
		return S2Dao_ConnectionUtil::prepareCall($con, $sql);
	}
}

if ( !S2Dao_BasicStatementFactory::$init ) {
    S2Dao_BasicStatementFactory::staticConst();
}
?>
