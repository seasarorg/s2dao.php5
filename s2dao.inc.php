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

/** S2Dao.PHP5 ROOT Directory */
define("S2DAO_PHP5", dirname(__FILE__) . "/src/s2dao.php5");

/**
 * Messages Resouce File
 */
define("S2DAO_PHP5_MESSAGES_INI", S2DAO_PHP5 . "/DaoMessages.properties");

require_once(S2DAO_PHP5 . "/S2DaoClassLoader.class.php");

if( class_exists("S2Container_MessageUtil") ){
    S2Container_MessageUtil::addMessageResource(S2DAO_PHP5_MESSAGES_INI);
}

if( class_exists("S2ContainerClassLoader") ){
    S2ContainerClassLoader::$USER_CLASSES = array_merge(
            S2ContainerClassLoader::$USER_CLASSES, S2DaoClassLoader::export());
}

/** use PEAR::DB */
require_once("DB.php");

?>
