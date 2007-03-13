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
class S2Dao_EntrySet extends S2Dao_HashSet {
    
    public function __construct(){
        parent::__construct();
    }
    
    public function add($element){
        parent::put($element, new S2Dao_MapEntry($element));
    }
    
    public function toArray(){
        return array_values(parent::toArray());
    }
    
    public function iterator(){
        $ao = new ArrayObject($this->toArray());
        return $ao->getIterator();
    }
}

/**
 * @author nowel
 */
class S2Dao_MapEntry {
    
    private $key;
    
    private $value;
    
    public function __construct(array $element){
        list($this->key, $this->value) = each($element);
    }
    
    public function getKey(){
        return $this->key;
    }
    
    public function getValue(){
        return $this->value;
    }
}

?>