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
interface S2Dao_DaoAnnotationReader {
    
    /** */
    const RETURN_OBJ = 0;
    /** */
    const RETURN_LIST = 1;
    /** */
    const RETURN_ARRAY = 2;
    /** */
    const RETURN_YAML = 3;
    /** */
    const RETURN_JSON = 4;
    /** */
    const RETURN_MAP = 5;

    /**
     * @param name
     * @return
     */
    public function getQuery(ReflectionMethod $method);
    
    /**
     * @param $method
     * @return 
     */
    public function getStoredProcedureName(ReflectionMethod $method);
    
    /**
     * @param method
     * @return
     */
    public function getArgNames(ReflectionMethod $method);
    
    /**
     * @param method 
     * @return
     */
    public function getBeanClass(ReflectionMethod $method = null);
    
    /**
     * @param methodName
     * @return
     */
    public function getNoPersistentProps(ReflectionMethod $method);
    
    /**
     * @param method
     * @return
     */
    public function getPersistentProps(ReflectionMethod $method);
    
    /**
     * @param method
     * @param suffix
     * @return
     */
    public function getSQL(ReflectionMethod $method, $suffix);
    
    /**
     * @param method
     * @return returnType
     */
    public function getReturnType(ReflectionMethod $method);
}
?>
