<?php

/**
 * @author nowel
 */
final class S2Dao_DbmsManager {

    private static $dbmses_ = null;
    private static $staticConst = false;

    private function S2Dao_DbmsManager() {
    }

    private static function staticConst(){
        self::$dbmses_ = new S2Dao_HashMap();

        $dbmsClassNames = new ArrayObject(
                                  parse_ini_file(S2DAO_PHP5 . "/dbms.properties")
                              );

        for($i = $dbmsClassNames->getIterator(); $i->valid(); $i->next() ){
            $path = S2DAO_PHP5 . DIRECTORY_SEPARATOR . 
                    str_replace(".", "/", $i->current()) . ".class.php";

            if( file_exists($path) ){
                require_once ($path);
                $productName = strtolower($i->key());
                $dbms = new $productName();
                self::$dbmses_->put($productName, $dbms);
            }
        }
    	
        self::$staticConst = true;
    }

    public static function getDbms($ds) {
        if( !self::$staticConst ){
            self::staticConst();
        }
        $match = "";
        $info = $ds->toString();
        preg_match( "/\w+: \(.*, dbsyntax=(\w+)\) \[(\w+)\]/i", $info, $match);
        return self::$dbmses_->get("s2dao_" . $match[1]);

        /*
        if( $ds instanceof DataSource ){
            $dbms = null;
            $con = S2Dao_DataSourceUtil::getConnection($ds);

            $dmd = S2Dao_ConnectionUtil::getMetaData($con);
            $dbms = self::getDbms($dmd);

            S2Dao_ConnectionUtil::close($con);

            return $dbms;

        } else if( is_string($ds) ){
            $dbms = self::$dbmses_->get($ds);
            if($dbms == null){
                $dbms = self::$dbmses_->get("");
            }
            return $dbms;
        } else {
            return self::getDbms(S2Dao_DatabaseMetaDataUtil::getDatabaseProductName($ds));
        }
        */
    }
}
?>
