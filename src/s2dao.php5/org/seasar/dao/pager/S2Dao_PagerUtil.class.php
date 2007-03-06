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
// | Authors: yonekawa                                                    |
// +----------------------------------------------------------------------+
// $Id: $
//
/**
 * ページャResultSetのユーティリティクラス
 * @author yonekawa
 * @package org.seasar.s2dao.pager
 */
class S2Dao_PagerUtil
{
    /**
     * listの内容をPagerConditionの条件でフィルタリングする。
     * @param $resultSet フィルタリング前の配列
     * @param $condition 条件(S2Dao_PagerCondition)
     * @return フィルタリング後の配列
     */
    public static function filter($resultSet, S2Dao_PagerCondition $condition)
    {
        if (empty($resultSet)) {
            return $resultSet;
        }
        if (!($condition instanceof S2Dao_PagerCondition)) {
            return $resultSet;
        }
        if ($condition->getLimit() == S2Dao_PagerCondition::NONE_LIMIT) {
            return $resultSet;
        }

        $returnArray = false;
        $retValue = new S2Dao_ArrayList();
        if(!($resultSet instanceof S2Dao_List)){
            $resultSet = new S2Dao_ArrayList(new ArrayObject($resultSet));
            $returnArray = true;
        }
        
        $condition->setCount($resultSet->size());
     
        $limit = $condition->getOffset() + $condition->getLimit();
        $count = $condition->getCount();
        $start = $condition->getOffset() == null ? 0 : $condition->getOffset();
        for($i = $start; $i < $limit && $i < $count; $i++){
            $retValue->add($resultSet->get($i));
        }

        if($returnArray){
            return $retValue->toArray();
        }
        return $retValue;
    }

    /**
     * jsonの内容をPagerConditionの条件でフィルタリングする。
     * @param $json      フィルタリング前のJSONデータ
     * @param $condition 条件(S2Dao_PagerCondition)
     * @return フィルタリング後のJSONデータ
     */
    public static function filterJson($json, $condition)
    {
        if (empty($json)) {
            return $json;
        }
        $resultSet = json_decode($json);
        $resultSet = self::filter($resultSet, $condition);
        return json_encode($resultSet);
    }
    
    /**
     * yamlの内容をPagerConditionの条件でフィルタリングする。
     * @param $yaml      フィルタリング前のYAMLデータ
     * @param $condition 条件(S2Dao_PagerCondition)
     * @return フィルタリング後のYAMLデータ
     */
    public static function filterYaml($yaml, $condition)
    {
        if (empty($yaml)) {
            return $yaml;
        }
        $spyc = new Spyc();
        $resultSet = $spyc->YAMLLoad($yaml);

        $resultSet = self::filter($resultSet, $condition);
        return $spyc->YAMLdump($resultSet);
    }

    /**
     * S2Pagerでフィルタリングされたlistの内容をページング情報を付加して返す。
     * @param $resultSet 
     * @param $condition 条件(S2Dao_PagerCondition)
     * @return ページング情報を付加した配列 
     */
    public static function createPagerObject($resultSet, S2Dao_PagerCondition $condition)
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
    
    public static function createPagerJsonObject($resultSet, S2Dao_PagerCondition $condition)
    {
        if (empty($resultSet)) {
            return $resultSet;
        }
        $pager = self::createPagerObject(json_decode($resultSet), $condition);

        return json_encode($pager);
    }
    
    public static function createPagerYamlObject($resultSet, S2Dao_PagerCondition $condition)
    {
        if (empty($resultSet)) {
            return $resultSet;
        }
        $spyc = new Spyc();
        $pager = self::createPagerObject($spyc->YAMLLoad($resultSet), $condition);

        return $spyc->YAMLdump($pager);
    }
}

?>