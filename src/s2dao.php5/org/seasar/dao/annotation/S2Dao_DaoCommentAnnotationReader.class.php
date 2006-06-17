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

    const SQL_SUFFIX = S2Dao_DaoConstantAnnotationReader::SQL_SUFFIX;
    const SELECT_ARRAY_NAME = '/@return\s*array/i';
    const SELECT_LIST_NAME = '/@return\s*list/i';
    const SELECT_YAML_NAME = '/@return\s*yaml/i';
    const SELECT_JSON_NAME = '/@return\s*json/i';
    const RETURN_TYPE_MAP = '/@return\s*map/i';
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
        return $anno->value;
    }

    public function getSQL(ReflectionMethod $method, $dbmsSuffix) {
        $dbmsSuffix = preg_replace('/^(_)/', '', $dbmsSuffix);
        $anno = $this->getMethodAnnotation('Sql', $method);
        if($anno != null && $anno->value != null){
            return $anno->value;
        }
        return null;
    }
    
    public function getStoredProcedureName(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('Procedure', $method);
        if($anno != null && $anno->value != null){
            return $anno->value;
        }
        return null;
    }
    
    public function getReturnType(ReflectionMethod $method){
        $comment = $this->getMethodComment($method);
        // FIXME
        if(preg_match(self::RETURN_TYPE_MAP, $comment)){
            return 'Map';
        }
        return null;
    }
    
    public function isSelectList(ReflectionMethod $method){
        $comment = $this->getMethodComment($method);
        return preg_match(self::SELECT_LIST_NAME, $comment);
    }
    
    public function isSelectArray(ReflectionMethod $method){
        $comment = $this->getMethodComment($method);
        return preg_match(self::SELECT_ARRAY_NAME, $comment);
    }
    
    public function isSelectYaml(ReflectionMethod $method){
        $comment = $this->getMethodComment($method);
        return preg_match(self::SELECT_YAML_NAME, $comment);
    }
    
    public function isSelectJson(ReflectionMethod $method){
        $comment = $this->getMethodComment($method);
        return preg_match(self::SELECT_JSON_NAME, $comment);
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