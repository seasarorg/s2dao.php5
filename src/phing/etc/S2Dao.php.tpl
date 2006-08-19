<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id$
//
/**
 * @author nowel
 *
 * User Definition
 *   User could define these definitions.
 *   - S2DAO_PHP5_USE_COMMENT : constant or comment annotation usage
 *      [ boolean: default false ]
 *   needs: sets property S2Container_AnnotationContainer::$DEFAULT_ANNOTATION_READER
 *      [S2Container_AnnotationContainer::$DEFAULT_ANNOTATION_READER = 'S2DaoAnnotationReader';]
 */
 
/**
 * PDO load check
 */
if(!extension_loaded('pdo')){
    print("[ERROR] requirement : PDO. exit.\n");
    exit;
}

/**
 * S2Dao.PHP5 ROOT Directory
 */
define('S2DAO_PHP5', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'S2Dao');
ini_set('include_path', 
        S2DAO_PHP5 . PATH_SEPARATOR . ini_get('include_path'));

/**
 * S2Dao.PHP5 Core Classes
 */
require_once 's2dao.core.classes.php';
//if(class_exists('S2ContainerClassLoader')){
//    S2ContainerClassLoader::import(S2DAO_PHP5);
//}

/**
 * Messages Resouce File
 */
if(class_exists('S2ContainerMessageUtil')){
    S2ContainerMessageUtil::addMessageResource(S2DAO_PHP5 . '/DaoMessages.properties');
}

//
// S2ContainerClassLoader::addLoader('S2DaoClassLoader');
//
?>