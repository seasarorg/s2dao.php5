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
// $Id: $
//
/**
 * @author nowel
 * @package org.seasar.s2dao
 */
class S2Dao_CaseInsensitiveSet extends S2Dao_CaseInsensitiveMap {
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * @throws S2Container_S2RuntimeException
     */
    public final function put($key, $value){
        throw new S2Container_S2RuntimeException('IllegalAccess', array(__METHOD__));
    }
    
    /**
     * @throws S2Container_S2RuntimeException
     */
    public final function keySet(){
        throw new S2Container_S2RuntimeException('IllegalAccess', array(__METHOD__));
    }
    
    /**
     * @throws S2Container_S2RuntimeException
     */
    public final function entrySet(){
        throw new S2Container_S2RuntimeException('IllegalAccess', array(__METHOD__));
    }
    
    public function add($element){
        parent::put($element, $element);
    }
    
    public function addAll(ArrayObject $list){
        $arrays = $list->getArrayCopy();
        foreach($arrays as $value){
            $this->add($value);
        }
    }
    
    public function toArray(){
        return array_values(parent::toArray());
    }
}

?>