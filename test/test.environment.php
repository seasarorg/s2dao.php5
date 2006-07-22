<?php
error_reporting(E_ALL & ~E_NOTICE | E_STRICT);
ini_set('memory_limit', '300M');

define('HOME_DIR',  dirname(dirname(__FILE__)));
define('SRC_DIR', HOME_DIR . '/src');
define('TEST_DIR', HOME_DIR . '/test');
define('PACKAGE_DIR', TEST_DIR . '/s2dao.php5/org/seasar');
define('SAMPLE_DIR', TEST_DIR . '/sample');
define('RESOURCE_DIR', TEST_DIR . '/resource');

require_once "S2Container/S2Container.php";
require_once HOME_DIR . '/S2Dao.php';
//require_once "S2Dao/S2Dao.php";
require_once "PHPUnit2/Framework/IncompleteTestError.php";

// Spyc use
include_once 'spyc.php';
// sqlite_procedure funcions
include_once TEST_DIR . "/classes/pdo_sqlite/procedure.php";

define('S2CONTAINER_PHP5_APP_DICON', RESOURCE_DIR . '/app.dicon');
define('S2CONTAINER_PHP5_LOG_LEVEL', S2Container_SimpleLogger::FATAL);
define('S2CONTAINER_PHP5_DOM_VALIDATE', false);
define('DAO_DICON', RESOURCE_DIR . '/dao.dicon');
define('PDO_DICON', RESOURCE_DIR . '/pdo-sqlite.dicon');
//define('PDO_DICON', RESOURCE_DIR . '/pdo-mysql.dicon');
//define('PDO_DICON', RESOURCE_DIR . '/pdo-pgsql.dicon');
//define('PDO_DICON', RESOURCE_DIR . '/pdo-oracle.dicon');
//define('PDO_DICON', RESOURCE_DIR . '/pdo-firebird.dicon');
//define('PDO_DICON', RESOURCE_DIR . '/pdo-sybase.dicon');

S2ContainerClassLoader::import(S2CONTAINER_PHP5);
S2ContainerClassLoader::import(S2DAO_PHP5);
S2ContainerClassLoader::import(TEST_DIR . "/classes/dao");
S2ContainerClassLoader::import(TEST_DIR . "/classes/extension/db");
S2ContainerClassLoader::import(TEST_DIR . "/classes/extension/dataset");
S2ContainerClassLoader::import(TEST_DIR . "/classes/extension/tx");
S2ContainerClassLoader::import(TEST_DIR . "/classes/sample/transaction");
S2ContainerClassLoader::import(TEST_DIR . "/classes/sample/transaction/dao");
S2ContainerClassLoader::import(TEST_DIR . "/classes/sample/transaction/entity");
S2ContainerClassLoader::import(TEST_DIR . "/classes/sample/search/dao");
S2ContainerClassLoader::import(TEST_DIR . "/classes/sample/search/impl");
S2ContainerClassLoader::import(TEST_DIR . "/classes/sample/search/entity");
S2ContainerClassLoader::import(TEST_DIR . "/classes/sample/procedure/dao");
S2ContainerClassLoader::import(TEST_DIR . "/classes/sample/procedure/entity");

function __autoload($class = null){
    S2ContainerClassLoader::load($class);
}
?>
