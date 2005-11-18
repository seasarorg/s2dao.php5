<?php
require_once dirname(dirname(dirname(__FILE__))) . "/s2container.inc.php";
require_once dirname(dirname(dirname(__FILE__))) . "/s2dao.inc.php";

/** __autoload function */
if( class_exists("S2ContainerClassLoader") ){
    
    function __autoload($class = null){
        if( S2ContainerClassLoader::load($class) ){
            return;
        }
    }
}
?>