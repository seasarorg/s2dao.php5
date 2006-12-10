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
 * ページャ条件保持オブジェクトのベースクラス。
 *
 * S2Dao_PagerConditionのデフォルト実装クラスです。
 * 独自検索条件DTOを実装する場合はこのクラスを継承するとよいでしょう。
 * @author yonekawa
 */
class S2Dao_DefaultPagerCondition implements S2Dao_PagerCondition
{
    /** 現在の位置 */
    private $offset = 0;

    /** 表示の最大値 */
    private $limit = self::NONE_LIMIT;

    /** 取得した総数 */
    private $count = 0;
    
    /**
     * @return 現在の総数を返す
     */
    public function getCount() 
    {
        return $this->count;
    }

    /**
     * @param total セットする総数
     */
    public function setCount($total) 
    {
        $this->count = $total;
    }

    /**
     * @return 現在のLimit
     */
    public function getLimit() 
    {
        return $this->limit;
    }

    /**
     * @param limit セットするLimit
     */
    public function setLimit($limit) 
    {
        $this->limit = $limit;
    }

    /**
     * @return 現在のOffset
     */
    public function getOffset() 
    {
        return $this->offset;
    }
    
    /**
     * @param offset セットするOffset
     */
    public function setOffset($offset) 
    {
        $this->offset = $offset;
    }
    
}

?>
