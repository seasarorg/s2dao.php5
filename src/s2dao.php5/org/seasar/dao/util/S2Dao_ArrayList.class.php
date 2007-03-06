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
// $Id$
//
/**
 * @author nowel
 */
class S2Dao_ArrayList extends ArrayObject {

    public function __construct($object = null){
        if($object === null){
            parent::__construct();
        } else {
            parent::__construct($object);
        }
    }
    
    public function size(){
        return $this->count();
    }
    
    public function isEmpty(){
        return count($this) == 0;
    }
    
    public function contains($object){
        return in_array($object, $this->getArrayCopy(), true);
    }
    
    public function get($index){
        return $this->offsetGet($index);
    }
	
    public function set($index, $object){
        $this->offsetSet($index, $object);
    }
    	
    public function add($index, $object = null){
        if($object == null){
            $this->append($index);
        } else {
            $this->offsetSet($index, $object);
        }
    }
    
    public function addAll(ArrayObject $list){
        $arrays = $list->getArrayCopy();
        foreach($arrays as $value){
            $this->append($value);
        }
    }
    
    public function remove($index){
        $this->offsetUnset($index);
    }
    
    public function iterator(){
        return $this->getIterator();
    }
    
    public function toArray(){
        return $this->getArrayCopy();
    }
}

?>
