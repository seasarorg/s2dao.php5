<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// $Id: $
//
/**
 * @author nowel
 * @package org.seasar.s2dao
 */
final class S2DaoDbmsManager {

    private static $dbmses = array(
        'Standard' => 'S2Dao_Standard',
        'mysql' => 'S2Dao_MySQL',
        'pgsql' => 'S2Dao_PostgreSQL',
        'sqlite' => 'S2Dao_SQLite',
        'firebird' => 'S2Dao_Firebird',
        'oci' => 'S2Dao_Oracle',
        'dblib' => 'S2Dao_Sybase',
        'sybase' => 'S2Dao_Sybase',
        'odbc' => 'S2Dao_DB2'
    );
    private static $instance = array();

    private function __construct() {
    }
    
    private static function getInstancate($driver){
        if(isset(self::$instance[$driver])){
            return self::$instance[$driver];
        }
        $class = null;
        if(isset(self::$dbmses[$driver])){
            $class = self::$dbmses[$driver];
        } else {
            $class = self::$dbmses['Standard'];
        }
        $instance = self::$instance[$driver] = new $class;
        return $instance;
    }

    public static function getDbms(PDO $ds) {
        return self::getInstancate($ds->getAttribute(PDO::ATTR_DRIVER_NAME));
    }
}
?>
