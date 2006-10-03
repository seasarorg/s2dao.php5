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
class S2Dao_DaoCommentAnnotationReader implements S2Dao_DaoAnnotationReader {

    const RETURN_TYPE_OBJ = '/@return\s*object/i';
    const RETURN_TYPE_ARRAY = '/@return\s*array/i';
    const RETURN_TYPE_LIST = '/@return\s*list/i';
    const RETURN_TYPE_YAML = '/@return\s*yaml/i';
    const RETURN_TYPE_JSON = '/@return\s*json/i';
    const RETURN_TYPE_MAP = '/@return\s*map/i';

    const FILTER_TYPE_PAGER = '/@filter\s*pager/i';

    protected $beanClass;
    
    public function __construct(S2Container_BeanDesc $beanDesc) {
        $this->beanClass = $beanDesc->getBeanClass();
    }
    
    public function getBeanClass() {
        $anno = S2Container_Annotations::getAnnotation('Dao',
                                $this->beanClass->getName());
        return new ReflectionClass(new $anno->bean);
    }

    public function getArgNames(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('Arguments', $method);
        if ($anno != null && 0 < count($anno->value)) {
            return $anno->value;
        } else {
            $params = array();
            foreach($method->getParameters() as $param){
                $params[] = $param->getName();
            }
            return $params;
        }
    }

    public function getNoPersistentProps(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('NoPersistentProperty', $method);
        if($anno != null && 0 < count($anno->value)){
            return $anno->value;
        }
        return null;
    }

    public function getPersistentProps(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('PersistentProperty', $method);
        if($anno != null && 0 < count($anno->value)){
            return $anno->value;
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
        $comment = $this->getMethodComment($method);
        if(preg_match(self::RETURN_TYPE_LIST, $comment)){
            return S2Dao_DaoAnnotationReader::RETURN_LIST;
        }
        if(preg_match(self::RETURN_TYPE_ARRAY, $comment)){
            return S2Dao_DaoAnnotationReader::RETURN_ARRAY;
        }
        if(preg_match(self::RETURN_TYPE_YAML, $comment)){
            return S2Dao_DaoAnnotationReader::RETURN_YAML;
        }
        if(preg_match(self::RETURN_TYPE_JSON, $comment)){
            return S2Dao_DaoAnnotationReader::RETURN_JSON;
        }
        if(preg_match(self::RETURN_TYPE_MAP, $comment)){
            return S2Dao_DaoAnnotationReader::RETURN_MAP;
        }
        if(preg_match(self::RETURN_TYPE_OBJ, $comment)){
            return S2Dao_DaoAnnotationReader::RETURN_OBJ;
        }
        return null;
    }

    public function getFilterType(ReflectionMethod $method)
    {
        $comment = $this->getMethodComment($method);
        if(preg_match(self::FILTER_TYPE_PAGER, $comment)){
            return S2Dao_DaoAnnotationReader::FILTER_PAGER;
        }
        return null;
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
    
    private function getMethodComment(ReflectionMethod $method){
        $method = $this->beanClass->getMethod($method->getName());
        return $method->getDocComment();
    }
}

?>