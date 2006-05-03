<?php
require_once 'S2Container/S2Container.php';
require_once 'S2Dao/S2Dao.php';

define('S2CONTAINER_PHP5_LOG_LEVEL', S2Container_SimpleLogger::DEBUG);
define('DAO_DICON', dirname(dirname(__FILE__)) . '/dao.dicon');
define('PDO_DICON', dirname(dirname(__FILE__)) . '/pdo.dicon');


/** class import */
if( class_exists("S2ContainerClassLoader") ){
    S2ContainerClassLoader::import(dirname(__FILE__) . "/dao");
    S2ContainerClassLoader::import(dirname(__FILE__) . "/entity");
    S2ContainerClassLoader::import(dirname(__FILE__) . "/impl");
}

if(class_exists('S2ContainerClassLoader')){
    S2ContainerClassLoader::import(S2CONTAINER_PHP5);
    function __autoload($class = null){
        if(S2ContainerClassLoader::load($class)){
            return;
        }
    }
}
?>