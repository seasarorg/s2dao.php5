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
 * ページャ条件保持オブジェクトのベースクラス。
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
     * @return Returns the total.
     */
    public function getCount() 
    {
        return $this->count;
    }

    /**
     * @param total The total to set.
     */
    public function setCount($total) 
    {
        $this->count = $total;
    }

    /**
     * @return Returns the limit.
     */
    public function getLimit() 
    {
        return $this->limit;
    }

    /**
     * @param limit The limit to set.
     */
    public function setLimit($limit) 
    {
        $this->limit = $limit;
    }

    /**
     * @return Returns the offset.
     */
    public function getOffset() 
    {
        return $this->offset;
    }
    
    /**
     * @param offset The offset to set.
     */
    public function setOffset($offset) 
    {
        $this->offset = $offset;
    }
    
}

?>
