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
class S2Dao_BeanCommentAnnotationReader implements S2Dao_BeanAnnotationReader {
    
    const Anno = 'Bean';
    private $beanClass;
    
    public function __construct(S2Container_BeanDesc $beanDesc) {
        $this->beanClass = $beanDesc->getBeanClass();
    }
    
    public function getTableAnnotation() {
        $anno = $this->getAnnotation();
        if($anno->table != ''){
            return $anno->table;
        }
        return null;
    }

    public function getColumnAnnotation(S2Container_PropertyDesc $pd) {
        $anno = $this->getPropertyAnnotation('Column', $pd);
        if($anno !== null){
            return $anno->value;
        }
        return $pd->getPropertyName();
    }

    public function getId(S2Container_PropertyDesc $pd) {
        $anno = $this->getPropertyAnnotation('Id', $pd);
        if($anno !== null){
            return $anno->value;
        }
        return null;
    }

    public function getNoPersisteneProps() {
        $anno = $this->getAnnotation('NoPersistentProperty');
        if(isset($anno->value)){
            return $anno->value;
        }
        return null;
    }

    public function getVersionNoPropertyNameAnnotation() {
        $anno = $this->getAnnotation('VersionNoProperty');
        if(isset($anno->value)){
            return $anno->value;
        }
        return null;
    }

    public function getTimestampPropertyName() {
        $anno = $this->getAnnotation('TimestampProperty');
        if(isset($anno->value)){
            return $anno->value;
        }
        return null;
    }
    
    public function getRelationNo(S2Container_PropertyDesc $pd) {
        $anno = $this->getPropertyAnnotation('Relation', $pd);
        if(isset($anno->relationNo)){
            return (int)$anno->relationNo;
        }
        return null;
    }

    public function getRelationKey(S2Container_PropertyDesc $pd) {
        $anno = $this->getPropertyAnnotation('Relation', $pd);
        if(isset($anno->relationKey)){
            return $anno->relationKey;
        }
        return null;
    }

    public function hasRelationNo(S2Container_PropertyDesc $pd) {
        return $this->getPropertyAnnotation('Relation', $pd) !== null;
    }
    
    public function getRelationTable(S2Container_PropertyDesc $pd) {
        // TODO: Comment Annotation
        return null;
    }
    
    public function getRelationType(S2Container_PropertyDesc $pd) {
        // TODO: Comment Annotation
        return null;
    }
    
    public function getRelationBean(S2Container_PropertyDesc $pd) {
        // TODO: Comment Annotation
        return null;
    }
    
    public function getValueType(S2Container_PropertyDesc $pd){
        // TODO: Comment Annotation
        return null;
    }
    
    private function getPropertyAnnotation($annoType, S2Container_PropertyDesc $pd){
        $propertyName = $pd->getPropertyName();
        if(S2Container_Annotations::isAnnotationPresent(
                $annoType, $this->beanClass, $propertyName)){
            return S2Container_Annotations::getAnnotation(
                $annoType, $this->beanClass, $propertyName);
        }
        return null;
    }
    
    private function getAnnotation($anno = self::Anno){
        $name = $this->beanClass->getName();
        if(S2Container_Annotations::isAnnotationPresent($anno, $name)){
            return S2Container_Annotations::getAnnotation($anno, $name);
        }
        return null;
    }
    
}

?>