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
abstract class S2Dao_AbstractAnnotationReader {
    
    protected $reader = null;

    public function __construct(S2Container_BeanDesc $beandesc) {
        if(defined('S2DAO_PHP5_USE_COMMENT')){
            if(S2DAO_PHP5_USE_COMMENT === true){
                $this->setCommentAnnotationHandler();
                $this->reader = $this->createCommentAnnotationReader($beandesc);
            }
        }
        
        if($this->reader === null){
            $this->reader = $this->createConstantAnnotationReader($beandesc);
        }
    }
    
    abstract protected function createConstantAnnotationReader($beandesc);
    abstract protected function createCommentAnnotationReader($beandesc);
    
    public function __call($name, $param){
        return call_user_func_array(array($this->reader, $name), $param);
    }
    
    private function setCommentAnnotationHandler(){
        if(class_exists('S2Container_AnnotationContainer')){
            if(strcasecmp(S2Container_AnnotationContainer::$DEFAULT_ANNOTATION_READER,
                      'S2DaoAnnotationReader') != 0){
               S2Container_AnnotationContainer::$DEFAULT_ANNOTATION_READER = 'S2DaoAnnotationReader';
            }
        }
    }

}

?>