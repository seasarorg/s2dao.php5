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
 */
class S2Dao_HashMap {
    
    protected $element = array();
    
    public function size(){
        return count($this->element);
    }
    
    public function isEmpty(){
        return empty($this->element);
    }
    
    public function get($key){
        if(!$this->containsKey($key)){
            return null;
        }
        return $this->element[$key];
    }
    
    public function put($key, $value){
        // TODO
        if(is_object($key)){
            $key = (string)$key;
        }
        $this->element[$key] = $value;
    }
    
    public function remove($key){
        if(!$this->containsKey($key)){
            return null;
        }
        $element = $this->element[$key];
        unset($this->element[$key]);
        return $element;
    }
    
    public function contains($key){
        return $this->containsKey($key);
    }
    
    public function containsKey($key){
        // TODO
        if(is_object($key)){
            $key = (string)$key;
        }
        return isset($this->element[$key]);
    }

    public function toArray(){
        return $this->element;
    }
    
    public function keySet(){
        $set = new S2Dao_ArrayList();
        $list = new ArrayObject(array_keys($this->toArray()));
        $set->addAll($list);
        return $set;
    }
}
?>
