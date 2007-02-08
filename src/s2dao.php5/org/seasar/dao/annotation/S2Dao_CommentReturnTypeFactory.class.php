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
 * @package org.seasar.s2dao.returntype
 */
class S2Dao_CommentReturnTypeFactory implements S2Dao_ReturnTypeFactory {
    
    const RETURN_TYPE_OBJ = '/@return\s*object/i';
    const RETURN_TYPE_ARRAY = '/@return\s*array/i';
    const RETURN_TYPE_LIST = '/@return\s*list/i';
    const RETURN_TYPE_YAML = '/@return\s*yaml/i';
    const RETURN_TYPE_JSON = '/@return\s*json/i';
    const RETURN_TYPE_MAP = '/@return\s*map/i';
    const TYPE_SUFFIX = '/@type\s*(.+)/';
    
    public function createReturnType(ReflectionMethod $method){
        $comment = $method->getDocComment();
        if(preg_match(self::TYPE_SUFFIX, $comment, $m)){
            return S2Dao_ReturnTypes::getValueType(trim($m[1]));
        }
        if(preg_match(self::RETURN_TYPE_LIST, $comment)){
            return S2Dao_ReturnTypes::getReturnType(S2Dao_ReturnType::Type_List);
        }
        if(preg_match(self::RETURN_TYPE_ARRAY, $comment)){
            return S2Dao_ReturnTypes::getReturnType(S2Dao_ReturnType::Type_Array);
        }
        if(preg_match(self::RETURN_TYPE_YAML, $comment)){
            return S2Dao_ReturnTypes::getReturnType(S2Dao_ReturnType::Type_Yaml);
        }
        if(preg_match(self::RETURN_TYPE_JSON, $comment)){
            return S2Dao_ReturnTypes::getReturnType(S2Dao_ReturnType::Type_Json);
        }
        if(preg_match(self::RETURN_TYPE_MAP, $comment)){
            return S2Dao_ReturnTypes::getReturnType(S2Dao_ReturnType::Type_Map);
        }
        return S2Dao_ReturnTypes::getReturnType(S2Dao_ReturnType::Type_Object);
    }
}

?>