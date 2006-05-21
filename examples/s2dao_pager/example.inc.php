<?php
require_once 'S2Container/S2Container.php';
//require_once 'S2Dao/S2Dao.php';

// debug
define('HOME', '/path/to/S2Dao.PHP5-Pager');
define('S2DAO_PHP5', HOME . '/src/s2dao.php5');
define('S2CONTAINER_PHP5_DOM_VALIDATE', false);
define('S2CONTAINER_PHP5_LOG_LEVEL', S2Container_SimpleLogger::DEBUG);
define('DAO_DICON', dirname(__FILE__) . '/resource/dao.dicon');
define('PDO_DICON', dirname(dirname(__FILE__)) . '/pdo.dicon');

require_once S2DAO_PHP5 . '/S2DaoClassLoader.class.php';

/** class import */
if(class_exists('S2ContainerClassLoader')){
    S2ContainerClassLoader::import(S2CONTAINER_PHP5);
    S2ContainerClassLoader::import(S2DaoClassLoader::export());
    S2ContainerClassLoader::import(dirname(__FILE__) . "/dao");
    S2ContainerClassLoader::import(dirname(__FILE__) . "/entity");
    function __autoload($class = null){
        if(S2ContainerClassLoader::load($class)){
            return;
        }
    }
}
?>
