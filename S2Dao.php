<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// $Id: $
//
/**
 * @author nowel
 *
 * S2Dao System Definition
 *   SDao define these definitions.
 *   - S2DAO_PHP5 : S2DAO.PHP5 ROOT Directory
 *     [ string default /src/s2dao.php5 ]
 * 
 * User Definition
 *   User could define these definitions.
 *   - S2DAO_PHP5_USE_COMMENT : constant or comment annotation usage
 *     [ boolean: default false ]
 *
 * Autoload function must be defined
 *   sample : use S2ContainerClassLoader
 *     S2ContainerClassLoader::import(S2CONTAINER_PHP5);
 *     S2ContainerClassLoader::import(S2DAO_PHP5);
 *     function __autoload($class = null){
 *         S2ContainerClassLoader::load($class);
 *     }
 * 
 *   sample : use include_once directly
 *     function __autoload($class=null){
 *         if($class != null){
 *             include_once("$class.class.php");
 *         }
 *     }
 * 
 */

require_once dirname(__FILE__) . '/build/s2dao.php5/S2Dao.php';
?>
