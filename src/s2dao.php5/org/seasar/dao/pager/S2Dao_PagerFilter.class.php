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
 * @author yonekawa
 */
class S2Dao_PagerFilter
{
    /**
     * @param $resultSet ResultSet
     */
    public function filterResultSet($resultSet, S2Container_MethodInvocation $invocation)
    {
        if (empty($resultSet)) {
            return $resultSet;
        }
        if (is_null($invocation)) {
            return $resultSet;
        }
        $args = $invocation->getArguments();
        if (count($args) < 1 || ! $args[0] instanceof S2Dao_PagerCondition) {
            return $resultSet;
        }
        $condition = $args[0];
 
        $method = $invocation->getMethod();
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($invocation->getTargetClass());
        $reader = new S2Dao_DaoCommentAnnotationReader($beanDesc);
        $filterType = $reader->getFilterType($method);

        if ($filterType == S2Dao_DaoAnnotationReader::FILTER_PAGER) {
            
            $type = $reader->getReturnType($method);
            if ($type == S2Dao_DaoAnnotationReader::RETURN_YAML) {
                return $this->createPagerYamlObject($resultSet, $condition);
            } else if ($type == S2Dao_DaoAnnotationReader::RETURN_JSON) {
                return $this->createPagerJsonObject($resultSet, $condition);
            } else {
                $reader = new S2Dao_DaoConstantAnnotationReader($beanDesc);
                $type = $reader->getReturnType($method);

                if ($type == S2Dao_DaoAnnotationReader::RETURN_YAML) {
                    return $this->createPagerYamlObject($resultSet, $condition);
                } else if ($type == S2Dao_DaoAnnotationReader::RETURN_JSON) {
                    return $this->createPagerJsonObject($resultSet, $condition);
                }
            }
            return $this->createPagerObject($resultSet, $condition);
        }
        return $resultSet;
    }

    private function getAnnotationReader(S2Container_MethodInvocation $invocation)
    {
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($invocation->getTargetClass());
        return new S2Dao_DaoCommentAnnotationReader($beanDesc);
    }

    private function createPagerObject($resultSet, S2Dao_PagerCondition $condition)
    {
        if (empty($resultSet)) {
            return $resultSet;
        }
        
        $helper = new S2Dao_PagerViewHelper($condition);
        
        $pager = array();
        $pager['data'] = $resultSet;
        $pager['status'] = array(
            'count' => $condition->getCount(),
            'limit' => $condition->getLimit(),
            'offset' => $condition->getOffset()
        );
        $pager['hasPrev'] = $helper->isPrev();
        $pager['hasNext'] = $helper->isNext();
        $pager['currentIndex'] = $helper->getPageIndex();
        $pager['isFirst'] = $helper->getDisplayPageIndexBegin() == $pager['currentIndex'] ? true : false;
        $pager['isLast'] = $helper->getDisplayPageIndexEnd() == $pager['currentIndex'] ? true : false;

        return $pager;
    }
    
    private function createPagerJsonObject($resultSet, S2Dao_PagerCondition $condition)
    {
        if (empty($resultSet)) {
            return $resultSet;
        }
        $pager = $this->createPagerObject(json_decode($resultSet), $condition);

        return json_encode($pager);
    }
    
    private function createPagerYamlObject($resultSet, S2Dao_PagerCondition $condition)
    {
        if (empty($resultSet)) {
            return $resultSet;
        }
        $spyc = new Spyc();
        $pager = $this->createPagerObject($spyc->YAMLLoad($resultSet), $condition);

        return $spyc->YAMLdump($pager);
    }
}

?>