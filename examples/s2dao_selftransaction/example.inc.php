<?php
require_once 'S2Container/S2Container.php';
require_once dirname(dirname(dirname(__FILE__))) . '/S2Dao.php';

define('PDO_DICON', dirname(__FILE__) . '/resource/pdo.dicon');
define('S2CONTAINER_PHP5_LOG_LEVEL', S2Container_SimpleLogger::DEBUG);
define('S2DAO_PHP5_USE_COMMENT', true);

if(class_exists('S2ContainerClassLoader')){
    S2ContainerClassLoader::import(S2CONTAINER_PHP5);
    S2ContainerClassLoader::import(dirname(__FILE__) . "/dao");
    S2ContainerClassLoader::import(dirname(__FILE__) . "/entity");
    S2ContainerClassLoader::import(dirname(__FILE__) . "/impl");
    function __autoload($class = null){
        if(S2ContainerClassLoader::load($class)){
            return;
        }
    }
}

if(class_exists('S2Container_AnnotationContainer')){
    S2Container_AnnotationContainer::$DEFAULT_ANNOTATION_READER = 'S2DaoAnnotationReader';
}

?>