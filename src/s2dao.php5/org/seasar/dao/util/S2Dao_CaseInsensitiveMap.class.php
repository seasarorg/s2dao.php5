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
class S2Dao_CaseInsensitiveMap extends S2Dao_HashMap {
    
    public function isEmpty($key = null){
        if($key === null){
            return empty($this->element);
        }
        $case = strtolower($key);
        return empty($this->element[$case]);
    }
    
    public function put($key, $value){
        $case = strtolower($key);
        $this->element[$case] = array($key => $value);
    }
    
    public function putAll(array $assoc){
        foreach($assoc as $key => $value){
            if(is_integer($key)){
                $case = (string)$key;
            } else {
                $case = strtolower($key);
            }
            $this->element[$case] = array($key => $value);
        }
    }
    
    public function get($key){
        if(!$this->containsKey($key)){
            return null;
        }
        $case = strtolower($key);
        return current($this->element[$case]);
    }
    
    public function remove($key){
        if(!$this->containsKey($key)){
            return null;
        }
        $case = strtolower($key);
        $element = current($this->element[$case]);
        unset($this->element[$case]);
        return $element;
    }
    
    public function containsKey($key){
        $case = strtolower($key);
        return array_key_exists($key, $this->element);
    }
    
    public function containsValue($value){
        return in_array($this->element, $value);
    }

    public function toArray(){
        $array = array();
        foreach($this->element as $element){
            list($key, $val) = each($element);
            $array[$key] = $val;
        }
        return $array;
    }
    
    public function valueSet(){
        $set = new S2Dao_ArrayList();
        $list = new ArrayObject(array_values($this->toArray()));
        $set->addAll($list);
        return $set;
    }
}
?>
