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
 * @package org.seasar.s2dao.annotation
 */
class S2Dao_FieldAnnotationReaderFactory implements S2Dao_AnnotationReaderFactory {
    
    const READER = 'S2DaoAnnotationReader';
    
    private $initialized = false;
    private $useCommentAnnotation = false;
    private $returnTypeFactory;
    
    public function __construct(){
    }
    
    public function initialize(){
        if(!$this->initialized && $this->useCommentAnnotation === true){
            $default = S2Container_AnnotationContainer::$DEFAULT_ANNOTATION_READER;
            $existsReader = (strcasecmp($default, self::READER) !== 0);
            if(class_exists('S2Container_AnnotationContainer') && $existsReader){
                S2Container_AnnotationContainer::$DEFAULT_ANNOTATION_READER = self::READER;
            }
            $this->initialized = true;
        }
    }
    
    public function isInitialized(){
        return $this->initialized;
    }
    
    public function setUseCommentAnnotation($bool){
        $this->useCommentAnnotation = (boolean)$bool;
    }
    
    public function getUseCommentAnnotation(){
        return (boolean)$this->useCommentAnnotation;
    }
    
    public function setReturnTypeFactory(S2Dao_ReturnTypeFactory $returnTypeFactory){
        $this->returnTypeFactory = $returnTypeFactory;
    }
    
    public function getReturnTypeFactory(){
        return $this->returnTypeFactory;
    }
    
    public function createDaoAnnotationReader(S2Container_BeanDesc $daoBeanDesc) {
        $reader = '';
        if($this->useCommentAnnotation){
            $reader = 'S2Dao_DaoCommentAnnotationReader';
        } else {
            $reader = 'S2Dao_DaoConstantAnnotationReader';
        }
        return new $reader($daoBeanDesc, $this->returnTypeFactory);
    }
    
    public function createBeanAnnotationReader($beanClass) {
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($beanClass);
        $reader = '';
        if($this->useCommentAnnotation){
            $reader = 'S2Dao_BeanCommentAnnotationReader';
        } else {
            $reader = 'S2Dao_BeanConstantAnnotationReader';
        }
        return new $reader($beanDesc);
    }
    
}

?>
