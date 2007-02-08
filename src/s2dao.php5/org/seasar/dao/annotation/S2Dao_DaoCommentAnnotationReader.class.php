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
 * @package org.seasar.s2dao.annotation
 */
class S2Dao_DaoCommentAnnotationReader implements S2Dao_DaoAnnotationReader {

    protected $beanClass;
    protected $returnTypeFactory;
    
    public function __construct(S2Container_BeanDesc $beanDesc,
                                S2Dao_ReturnTypeFactory $returnTypeFactory) {
        $this->beanClass = $beanDesc->getBeanClass();
        $this->returnTypeFactory = $returnTypeFactory;
    }
    
    public function getBeanClass(ReflectionMethod $method = null) {
        $anno = S2Container_Annotations::getAnnotation('Dao', $this->beanClass->getName());
        /*
        if($returnType === null){
            // valueType = ValueTypes.getValueType(returnType);
            //return createReturnClass($method);
        }
        */
        return new ReflectionClass(new $anno->bean);
    }

    public function getArgNames(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('Arguments', $method);
        if ($anno != null && 0 < count($anno->value)) {
            return $anno->value;
        } else {
            $argNames = array();
            $parameters = $method->getParameters();
            foreach($parameters as $param){
                $argNames[] = $param->getName();
            }
            return $argNames;
        }
    }

    public function getNoPersistentProps(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('NoPersistentProperty', $method);
        if($anno != null && 0 < count($anno->value)){
            return (array)$anno->value;
        }
        return null;
    }

    public function getPersistentProps(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('PersistentProperty', $method);
        if($anno != null && 0 < count($anno->value)){
            return (array)$anno->value;
        }
        return null;
    }

    public function getQuery(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('Query', $method);
        if($anno != null && isset($anno->value)){
            return $anno->value;
        }
        return null;
    }

    public function getSQL(ReflectionMethod $method, $dbmsSuffix) {
        $dbmsSuffix = preg_replace('/^(_)/', '', $dbmsSuffix);
        $anno = $this->getMethodAnnotation('Sql', $method);
        if($anno != null && isset($anno->value)){
            return $anno->value;
        }
        return null;
    }
    
    public function getStoredProcedureName(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('Procedure', $method);
        if($anno != null && isset($anno->value)){
            return $anno->value;
        }
        return null;
    }
    
    public function getReturnType(ReflectionMethod $method){
        return $this->returnTypeFactory->createReturnType($method);
    }

    private function getMethodAnnotation($annoType, ReflectionMethod $method){
        if(S2Container_Annotations::isAnnotationPresent($annoType,
                                                      $this->beanClass,
                                                      $method->getName())){
            return S2Container_Annotations::getAnnotation($annoType,
                                                          $this->beanClass,
                                                          $method->getName());
        }
        return null;
    }
    
}

?>