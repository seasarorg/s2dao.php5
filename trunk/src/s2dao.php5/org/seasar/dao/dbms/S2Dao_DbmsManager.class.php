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
        $dbmsClassNames = parse_ini_file(S2DAO_PHP5 . '/dbms.properties');
        foreach($dbmsClassNames as $key => $value){
            self::$dbmses_->put(strtolower($key), new $value);
        }
        self::$staticConst = true;
    }

    public static function getDbms($ds) {
        if(!self::$staticConst){
            self::staticConst();
        }
        $info = $ds->getAttribute(PDO::ATTR_DRIVER_NAME);
        return self::$dbmses_->get($info);
    }
}
?>
