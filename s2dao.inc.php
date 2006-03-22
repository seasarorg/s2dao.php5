<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2004-2005 the Seasar Foundation and the Others.            |
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
/**
 * @author nowel
 */

/** S2Dao.PHP5 ROOT Directory */
define("S2DAO_PHP5", dirname(__FILE__) . "/src/s2dao.php5");

/** PDO DataSource dicon */
//define("PDO_DICON", dirname(__FILE__) . "/pdo.dicon");

/** Phar Using Sample */
//define("S2DAO_PHP5_Phar", dirname(__FILE__) . "/build/s2dao.php5-1.0.0-beta3.phar");
//if( file_exists(S2DAO_PHP5_Phar) ){
//    require_once(S2DAO_PHP5_Phar);
//    define("S2DAO_PHP5", "phar://s2dao.php5-1.0.0-beta3.phar");
//}

/** S2Dao.PHP5 ClassLoader */
require_once(S2DAO_PHP5 . "/S2DaoClassLoader.class.php");

if( class_exists("S2ContainerClassLoader") ){
    S2ContainerClassLoader::import(S2DaoClassLoader::export());
}
if( class_exists("S2ContainerMessageUtil") ){
    S2ContainerMessageUtil::addMessageResource(S2DAO_PHP5."/DaoMessages.properties");
}

?>
