<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id$
//
/**
 * @author nowel
 */
final class S2Dao_DbmsManager {

    private static $dbmses = null;
    private static $staticConst = false;

    private function S2Dao_DbmsManager() {
    }

    private static function staticConst(){
        self::$dbmses = new S2Dao_HashMap();
        $dbmsClassNames = parse_ini_file(S2DAO_PHP5 . '/dbms.properties');
        foreach($dbmsClassNames as $key => $value){
            self::$dbmses->put(strtolower($key), new $value);
        }
        self::$staticConst = true;
    }

    public static function getDbms(PDO $ds) {
        if(!self::$staticConst){
            self::staticConst();
        }
        return self::$dbmses->get($ds->getAttribute(PDO::ATTR_DRIVER_NAME));
    }
}
?>
