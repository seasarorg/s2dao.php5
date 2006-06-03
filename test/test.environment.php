<?php
error_reporting(E_ALL & ~E_NOTICE | E_STRICT);

define('HOME_DIR',  dirname(dirname(__FILE__)));
define('SRC_DIR', HOME_DIR . '/src');
define('TEST_DIR', HOME_DIR . '/test');
define('PACKAGE_DIR', TEST_DIR . '/s2dao.php5/org/seasar');
define('RESOURCE_DIR', TEST_DIR . '/resource');

require_once "S2Container/S2Container.php";
require_once HOME_DIR . '/S2Dao.php';
//require_once "S2Dao/S2Dao.php";
require_once "PHPUnit2/Framework/IncompleteTestError.php";

define('S2CONTAINER_PHP5_APP_DICON', RESOURCE_DIR . '/app.dicon');
define('S2CONTAINER_PHP5_LOG_LEVEL', S2Container_SimpleLogger::DEBUG);
define('S2CONTAINER_PHP5_DOM_VALIDATE', false);
define('DAO_DICON', RESOURCE_DIR . '/dao.dicon');
define('DAO_PAGER_DICON', RESOURCE_DIR . '/dao-pager.dicon');
define('PDO_DICON', RESOURCE_DIR . '/pdo.dicon');

S2ContainerClassLoader::import(S2CONTAINER_PHP5);
S2ContainerClassLoader::import(S2DAO_PHP5);
S2ContainerClassLoader::import(TEST_DIR . "/classes");

function __autoload($class = null){
    S2ContainerClassLoader::load($class);
}
?>
