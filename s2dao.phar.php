<?php

define("S2DAO_PHP5_Phar", dirname(__FILE__) . "/build/s2dao.php5-1.0.0-beta3.phar");

if( file_exists(S2DAO_PHP5_Phar) ){
    require_once(S2DAO_PHP5_Phar);
    define("S2DAO_PHP5", "phar://s2dao.php5-1.0.0-beta3.phar");
} else {
    define("S2DAO_PHP5", dirname(__FILE__) . "/src/s2dao.php5");
    require_once(S2DAO_PHP5 . "/S2DaoClassLoader.class.php");
}

if( class_exists("S2ContainerClassLoader") ){
    S2ContainerClassLoader::import(S2DaoClassLoader::export());
}
if( class_exists("S2Container_MessageUtil") ){
    S2ContainerMessageUtil::addMessageResource(S2DAO_PHP5."/DaoMessages.properties");
}
?>
