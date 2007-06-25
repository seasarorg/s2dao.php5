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
 * @package org.seasar.s2dao
 */
class S2DaoClassLoader implements S2Dao_Autoload {

    private static $INSTANCE = null;

    private $loaders = array();

    /**
     * a constructor for private
     */
    private function __construct() {
    }

    private static function getInstance(){
        if(self::$INSTANCE === null){
            self::$INSTANCE = new self;
        }
        return self::$INSTANCE;
    }

    public static function add(array $packages, $scopedir = null){
        if(is_null($scopedir)){
            $scopedir = dirname(__FILE__);
        }
        $instance = self::getInstance();
        $instance->addLoader($packages, $scopedir);
        return $instance;
    }

    public final function __load($className){
        $c = count($this->loaders);
        for($i = 0; $i < $c; $i++){
            if($this->load($this->loaders[$i], $className)){
                return true;
            }
        }
        return false;
    }

    protected function addLoader(array $packages, $scopedir){
        foreach($packages as $package){
            $this->loaders[] = new S2DaoPackageloader($scopedir, $package);
        }
    }

    protected function load(S2Dao_Autoload $loader, $className){
        return $loader->__load($className);
    }
}

?>
