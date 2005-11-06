<?php

/**
 * @author nowel
 */
class BasicStatementFactory implements StatementFactory {
	
	public static $INSTANCE = null;
    public static $init = false;
    
    public static function staticConst(){
        self::$INSTANCE = new BasicStatementFactory();
        self::$init = true; 
    }
	
	public function createPreparedStatement(Connection $con, $sql) {
		return ConnectionUtil::prepareStatement($con, $sql);
	}
	
	public function createCallableStatement(Connection $con, $sql) {
		return ConnectionUtil::prepareCall($con, $sql);
	}
}

if ( !BasicStatementFactory::$init ) {
    BasicStatementFactory::staticConst();
}
?>
