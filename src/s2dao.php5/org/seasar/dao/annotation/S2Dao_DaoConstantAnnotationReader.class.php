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
class S2Dao_DaoConstantAnnotationReader implements S2Dao_DaoAnnotationReader {
    
    const BEAN = 'BEAN';
    const PROCEDURE_SUFFIX = '_PROCEDURE';
    const ARGS_SUFFIX = '_ARGS';
    const SQL_SUFFIX = '_SQL';
    const RET_SUFFIX = '_RET';
    const QUERY_SUFFIX = '_QUERY';
    const NO_PERSISTENT_PROPS_SUFFIX = '_NO_PERSISTENT_PROPS';
    const PERSISTENT_PROPS_SUFFIX = '_PERSISTENT_PROPS';
    const RETURN_TYPE_OBJ = '/Obj$/i';
    const RETURN_TYPE_ARRAY = '/Array$/i';
    const RETURN_TYPE_LIST = '/List$/i';
    const RETURN_TYPE_YAML = '/Yaml$/i';
    const RETURN_TYPE_JSON = '/Json$/i';
    const RETURN_TYPE_MAP = '/Map$/i';

    protected $daoBeanDesc;
    protected $interfacesBeanDesc = array();
    
    public function __construct(S2Container_BeanDesc $daoBeanDesc) {
        $this->daoBeanDesc = $daoBeanDesc;
        $interfaces = $daoBeanDesc->getBeanClass()->getInterfaces();
        $ifDesc = array();
        foreach($interfaces as $interface){
            $ifDesc[] = S2Container_BeanDescFactory::getBeanDesc($interface); 
        }
        $interfacesBeanDesc = $ifDesc;
    }

    public function getArgNames(ReflectionMethod $method) {
        $argsKey = $method->getName() . self::ARGS_SUFFIX;
        if ($this->daoBeanDesc->hasConstant($argsKey)) {
            $argNames = $this->daoBeanDesc->getConstant($argsKey);
            return S2Dao_ArrayUtil::spacetrim(explode(',', $argNames));
        } else {
            $returnType = $this->getReturnType($method);
            if($returnType == null){
                return array();
            }
            $argNames = array();
            $parameters = $method->getParameters();
            foreach($parameters as $param){
                $argNames[] = $param->getName();
            }
            return $argNames;
        }
    }
    
    public function getReturnComponentClass(ReflectionMethod $method) {
        $key = $method->getName() . self::RET_SUFFIX;
        if ($this->daoBeanDesc->hasConstant($key)) {
            $clazz = $this->daoBeanDesc->getConstant($key);
            return new ReflectionClass($clazz);
        }
        return null;
    }

    public function getQuery(ReflectionMethod $method) {
        $key = $method->getName() . self::QUERY_SUFFIX;
        if ($this->daoBeanDesc->hasConstant($key)) {
            return $this->daoBeanDesc->getConstant($key);
        }
        return null;
    }

    public function getBeanClass(ReflectionMethod $method = null) {
        $beanClass = $this->getBeanClass0($this->daoBeanDesc, $method);
        if($beanClass !== null){
            return $beanClass;
        }
        $c = count($this->interfacesBeanDesc);
        for ($i = 0; $i < $c; $i++) {
            $beanClass = $this->getBeanClass($this->interfacesBeanDesc[$i], $method);
            if($beanClass === null){
                continue;
            }
            return $beanClass;
        }
        return null;
    }
    
    private function getBeanClass0(S2Container_BeanDesc $beanDesc,
                                   ReflectionMethod $method = null){
        $beanField = $this->daoBeanDesc->getConstant(self::BEAN);
        $daoAnnotationClass = new ReflectionClass($beanField);
        if(null === $method){
            return $daoAnnotationClass;
        }
        $key = $method->getName() . '_' + self::BEAN;
        if ($this->daoBeanDesc->hasConstant($key)) {
            $queryField = $this->daoBeanDesc->getConstant($key);
            return new ReflectionClass($queryField);
        }
        
        $rType = null;
        $returnType = $this->getReturnType($method);
        if($returnType == self::RETURN_ARRAY){
            // TODO: MapArray or it
        }
        if($returnType == self::RETURN_MAP ||
           $returnType == self::RETURN_LIST){
            // XXX
            return $daoAnnotationClass;
        }
        /*
        if($returnType === null){
            // valueType = ValueTypes.getValueType(returnType);
            //return createReturnClass($method);
        }
        */
        return $daoAnnotationClass;
    }

    public function getNoPersistentProps(ReflectionMethod $method) {
        return $this->getProps($method, $method->getName() . self::NO_PERSISTENT_PROPS_SUFFIX);
    }

    public function getPersistentProps(ReflectionMethod $method) {
        return $this->getProps($method, $method->getName() . self::PERSISTENT_PROPS_SUFFIX);
    }

    public function getSQL(ReflectionMethod $method, $dbmsSuffix) {
        $key = $method->getName() . $dbmsSuffix . self::SQL_SUFFIX;
        if ($this->daoBeanDesc->hasConstant($key)) {
            return $this->daoBeanDesc->getConstant($key);
        }
        $key = $method->getName() . self::SQL_SUFFIX;
        if ($this->daoBeanDesc->hasConstant($key)) {
            return $this->daoBeanDesc->getConstant($key);
        }
        return null;
    }
    
    public function getStoredProcedureName(ReflectionMethod $method) {
        $key = $method->getName() . self::PROCEDURE_SUFFIX;
        if ($this->daoBeanDesc->hasConstant($key)) {
            return $this->daoBeanDesc->getConstant($key);
        }
        return null;
    }
    
    public function getReturnType(ReflectionMethod $method){
        $methodName = $method->getName();
        if(preg_match(self::RETURN_TYPE_LIST, $methodName)){
            return self::RETURN_LIST;
        }
        if(preg_match(self::RETURN_TYPE_ARRAY, $methodName)){
            return self::RETURN_ARRAY;
        }
        if(preg_match(self::RETURN_TYPE_YAML, $methodName)){
            return self::RETURN_YAML;
        }
        if(preg_match(self::RETURN_TYPE_JSON, $methodName)){
            return self::RETURN_JSON;
        }
        if(preg_match(self::RETURN_TYPE_MAP, $methodName)){
            return self::RETURN_MAP;
        }
        if(preg_match(self::RETURN_TYPE_OBJ, $methodName)){
            return self::RETURN_OBJ;
        }
        return null;
    }
    
    private function getProps(ReflectionMethod $method, $constName){
        if ($this->daoBeanDesc->hasConstant($constName)) {
            $s = $this->daoBeanDesc->getConstant($constName);
            return S2Dao_ArrayUtil::spacetrim(explode(',', $s));
        }
        return null;
    }
}

?>