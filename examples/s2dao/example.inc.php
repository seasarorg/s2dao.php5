<?php
require dirname(dirname(__FILE__)) . "/example.inc.php";

/** class import */
if( class_exists("S2ContainerClassLoader") ){
    S2ContainerClassLoader::import(dirname(__FILE__) . "/dao");
    S2ContainerClassLoader::import(dirname(__FILE__) . "/entity");
    S2ContainerClassLoader::import(dirname(__FILE__) . "/impl");
}

?>