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
// | Authors: yonekawa                                                    |
// +----------------------------------------------------------------------+
// $Id$
//
/**
 * PagerResultSetWrapperのファクトリクラス
 * @author yonekawa
 * @author nowel
 */
class S2Dao_PagerResultSetWrapperFactory
{
    /**
     * コメントアノテーションからDaoの結果のタイプを取得して、それに応じたResultSetWrapperを返す
     */
    public static function create(S2Container_MethodInvocation $invocation)
    {
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($invocation->getTargetClass());
        $reader = new S2Dao_DaoCommentAnnotationReader($beanDesc);

        // 戻り値の型を取得する
        $method = $invocation->getMethod();
        $type = $reader->getReturnType($method);
        
        if($type == S2Dao_DaoAnnotationReader::RETURN_YAML){
            return new S2Dao_PagerYamlResultSetWrapper();
        } else if ($type == S2Dao_DaoAnnotationReader::RETURN_JSON) {
            return new S2Dao_PagerJsonResultSetWrapper();
        } else {
            $methodName = $method->getName();
            if (preg_match('/Yaml$/', $methodName)) {
                return new S2Dao_PagerYamlResultSetWrapper();
            } else if (preg_match('/Json$/', $methodName)) {
                return new S2Dao_PagerJsonResultSetWrapper();
            }
            return new S2Dao_PagerBasicResultSetWrapper();
        }
    }
}

?>