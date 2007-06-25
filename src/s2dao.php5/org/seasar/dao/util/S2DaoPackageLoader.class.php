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
 * @package org.seasar.s2dao.util
 */
class S2DaoPackageLoader implements S2Dao_Autoload {

    private $dir;

    public function __construct($basedir, $package = null){
        $sep = DIRECTORY_SEPARATOR;
        if(is_null($basedir) && strcmp('', $package) === 0){
            $this->dir = $basedir . $sep;
        } else {
            $this->dir = $basedir . $sep . $package . $sep;
        }
    }

    public function __load($className){
        $ext = explode(',', spl_autoload_extensions());
        foreach($ext as $e){
            $path = $this->dir . $className . $e;
            if(file_exists($path)){
                require_once $path;
                return true;
            }
        }
        return false;
    }

}

?>
