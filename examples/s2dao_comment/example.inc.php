<?php
require dirname(dirname(__FILE__)) . "/example.inc.php";

if(class_exists('S2Container_AnnotationContainer')){
    S2Container_AnnotationContainer::$DEFAULT_ANNOTATION_READER = 'S2DaoAnnotationReader';
}

if(class_exists("S2ContainerClassLoader")){
    S2ContainerClassLoader::import(dirname(__FILE__) . "/dao");
    S2ContainerClassLoader::import(dirname(__FILE__) . "/entity");
}

?>