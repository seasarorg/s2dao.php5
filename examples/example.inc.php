<?php
require_once dirname(dirname(__FILE__)) . "/s2container.inc.php";
require_once dirname(dirname(__FILE__)) . "/s2dao.inc.php";

define("PDO_DICON", dirname(__FILE__) . "/pdo.dicon");

define('S2CONTAINER_PHP5_LOG_LEVEL', S2Container_SimpleLogger::DEBUG);

/** __autoload function */
if( class_exists("S2ContainerClassLoader") ){
    function __autoload($class = null){
        if( S2ContainerClassLoader::load($class) ){
            return;
        }
    }
}
?>