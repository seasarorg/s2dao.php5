<?php
require_once 'S2Container/S2Container.php';
//require_once 'S2Dao/S2Dao.php';

// test
define('S2DAO_PHP5', '/home/nowel/workspace/S2Dao.PHP5/src/s2dao.php5');
include_once S2DAO_PHP5 . '/S2DaoClassLoader.class.php';
//

define('S2CONTAINER_PHP5_LOG_LEVEL', S2Container_SimpleLogger::DEBUG);
define('DAO_DICON', dirname(dirname(__FILE__)) . '/dao.dicon');
define('PDO_DICON', dirname(dirname(__FILE__)) . '/pdo.dicon');

/** class import */
if(class_exists('S2ContainerClassLoader')){
    S2ContainerClassLoader::import(S2CONTAINER_PHP5);
    //S2ContainerClassLoader::import(S2DAO_PHP5);
    //
    if(class_exists('S2DaoClassLoader')){
        S2ContainerClassLoader::import(S2DaoClassLoader::export());
    }
    if(class_exists('S2ContainerMessageUtil')){
        S2ContainerMessageUtil::addMessageResource(S2DAO_PHP5 . '/DaoMessages.properties');
    }
    //
    S2ContainerClassLoader::import(dirname(__FILE__) . "/dao");
    S2ContainerClassLoader::import(dirname(__FILE__) . "/entity");
    S2ContainerClassLoader::import(dirname(__FILE__) . "/impl");
    function __autoload($class = null){
        if(S2ContainerClassLoader::load($class)){
            return;
        }
    }
}
?>
