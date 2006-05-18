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
 */
class S2Dao_ProcedureInfo {
    
    private $catalog = '';
    private $scheme = '';
    private $name = '';
    private $type = '';
    
    public function __construc($catalog, $scheme, $name, $type){
        $this->catalog = $catalog;
        $this->scheme = $scheme;
        $this->name = $name;
        $this->type = $type;
    }
    
    public function setCatalog($catalog){
        $this->catalog = $catalog;
    }
    
    public function getCatalog(){
        return $this->catalog;
    }
    
    public function setScheme($scheme){
        $this->scheme = $scheme;
    }
    
    public function getScheme(){
        return $this->scheme;
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setType($type){
        $this->type = $type;
    }
    
    public function getType(){
        return $this->type;
    }
}

?>