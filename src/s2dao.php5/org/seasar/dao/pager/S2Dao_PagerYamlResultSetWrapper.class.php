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
// $Id: $
//
/**
 * S2Daoの検索結果セット( YAML )をラップするクラス
 *
 * S2Daoの検索結果セット( YAML )をコンディションDTOでラップするクラスです。
 * @author yonekawa
 */
class S2Dao_PagerYamlResultSetWrapper implements S2Dao_PagerResultSetWrapper
{
    /**
     * S2Daoの結果(YAML)をDTOの条件でラップして返します
     * @param $result S2Daoの結果セット(YAML)
     * @param $condition 検索条件DTO
     */
    public function filter($result, $condition)
    {
        return S2Dao_PagerUtil::filterYaml($result, $condition);
    }
}

?>