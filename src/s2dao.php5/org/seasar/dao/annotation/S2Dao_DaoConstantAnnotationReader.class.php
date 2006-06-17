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
class S2Dao_DaoConstantAnnotationReader implements S2Dao_DaoAnnotationReader {
    
    const BEAN = 'BEAN';
    const ARGS_SUFFIX = '_ARGS';
    const SQL_SUFFIX = '_SQL';
    const QUERY_SUFFIX = '_QUERY';
    const NO_PERSISTENT_PROPS_SUFFIX = '_NO_PERSISTENT_PROPS';
    const PERSISTENT_PROPS_SUFFIX = '_PERSISTENT_PROPS';
    const PROCEDURE_SUFFIX = "_PROCEDURE";
    const SELECT_ARRAY_NAME = '/Array$/i';
    const SELECT_LIST_NAME = '/List$/i';
    const SELECT_YAML_NAME = '/Yaml$/i';
    const SELECT_JSON_NAME = '/Json$/i';
    const RETURN_TYPE_MAP = '/Map$/i';

    protected $daoBeanDesc;
    
    public function __construct(S2Container_BeanDesc $daoBeanDesc) {
        $this->daoBeanDesc = $daoBeanDesc;
    }

    public function getArgNames(ReflectionMethod $method) {
        $argsKey = $method->getName() . self::ARGS_SUFFIX;
        if ($this->daoBeanDesc->hasConstant($argsKey)) {
            $argNames = $this->daoBeanDesc->getConstant($argsKey);
            return S2Dao_ArrayUtil::spacetrim(explode(',', $argNames));
        } else {
            $params = array();
            foreach($method->getParameters() as $param){
                $params[] = $param->getName();
            }
            return $params;
        }
    }

    public function getQuery(ReflectionMethod $method) {
        $key = $method->getName() . self::QUERY_SUFFIX;
        if ($this->daoBeanDesc->hasConstant($key)) {
            return $this->daoBeanDesc->getConstant($key);
        }
        return null;
    }

    public function getBeanClass() {
        $beanField = $this->daoBeanDesc->getConstant(self::BEAN);
        return new ReflectionClass(new $beanField);
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
        if(preg_match(self::RETURN_TYPE_MAP, $method->getName())){
            return 'Map';
        }
        return null;
    }
    
    public function isSelectList(ReflectionMethod $method){
        return preg_match(self::SELECT_LIST_NAME, $method->getName());
    }
    
    public function isSelectArray(ReflectionMethod $method){
        return preg_match(self::SELECT_ARRAY_NAME, $method->getName());
    }
    
    public function isSelectYaml(ReflectionMethod $method){
        return preg_match(self::SELECT_YAML_NAME, $method->getName());
    }
    
    public function isSelectJson(ReflectionMethod $method){
        return preg_match(self::SELECT_JSON_NAME, $method->getName());
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