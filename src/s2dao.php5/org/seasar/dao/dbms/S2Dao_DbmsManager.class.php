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
                self::$dbmses_->put($productName, new $productName());
            }
        }
        self::$staticConst = true;
    }

    public static function getDbms($ds) {
        if( !self::$staticConst ){
            self::staticConst();
        }
        $match = "";
        $info = $ds->getAttribute(PDO::ATTR_DRIVER_NAME);
        return self::$dbmses_->get("s2dao_" . $info);
    }
}
?>
