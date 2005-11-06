<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003-2004 The Seasar Project.                          |
// +----------------------------------------------------------------------+
// | The Seasar Software License, Version 1.1                             |
// |   This product includes software developed by the Seasar Project.    |
// |   (http://www.seasar.org/)                                           |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
//
// $Id$
/**
 * @author nowel
 */

/** -------------- S2Container.PHP5 Setting -----------------------------*/

/** S2Container.PHP5 ROOT Directory */
define('S2CONTAINER_PHP5', dirname(__FILE__) . "/src/s2container.php5");
/** S2Dao.PHP5 ROOT Ditrctory */
define("S2DAO_PHP5", dirname(__FILE__) . "/src/s2dao.php5");

/** DICON XML format DTD Validation Switch */
define("S2CONTAINER_PHP5_DOM_VALIDATE", false);

/**
 * Messages Resouce File
 * S2Container.php5 と S2Dao.php5 のメッセージをマージしたものです。
 */
define("S2CONTAINER_PHP5_MESSAGES_INI", S2DAO_PHP5 . "/SSRMessages.properties");

/** S2Container.PHP5 Log Level */
//define('S2CONTAINER_PHP5_LOG_LEVEL',SimpleLogger::WARN);

/** SingletonS2ContainerFactory app.dicon */
//define('S2CONTAINER_PHP5_APP_DICON','app.dicon');

require_once(S2CONTAINER_PHP5 . "/S2ContainerClassLoader.class.php");
require_once(S2CONTAINER_PHP5 . "/s2container.core.classes.php");
require_once(S2DAO_PHP5 . "/S2DaoClassLoader.class.php");

/** if use PEAR::DB */
require_once("DB.php");

if( function_exists("__autoload") ){
    exit("sorry. is already __autoload()");
}

// when beta2
function unsetS2ContainerClsLoderKey($key){
    if(isset(S2ContainerClassLoader::$CLASSES[$key])){
        unset(S2ContainerClassLoader::$CLASSES[$key]);
    }
}

/** __autoload function */
if( class_exists("S2ContainerClassLoader") && class_exists("S2DaoClassLoader") ){

    // unset
    unsetS2ContainerClsLoderKey("DaoMetaData");
    unsetS2ContainerClsLoderKey("DaoMetaDataImpl");
    unsetS2ContainerClsLoderKey("DaoMetaDataFactory");
    unsetS2ContainerClsLoderKey("DaoMetaDataFactoryImpl");
    unsetS2ContainerClsLoderKey("SqlCommand");
    
    function __autoload($class = null){
        if( S2ContainerClassLoader::load($class) ){
            return;
        }
        if( S2DaoClassLoader::load($class) ){
            return;
        }
    }
}

?>
