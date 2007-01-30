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
 * ページャViewのヘルパークラス
 * @author yonekawa
 */
class S2Dao_PagerViewHelper implements S2Dao_PagerCondition
{
    /** 画面上でのページの最大表示件数のデフォルト  */
    const DEFAULT_DISPLAY_PAGE_MAX = 9;
    
    /** 検索条件オブジェクト */
    private $condition;

    /** 画面上でのページの最大表示件数 */
    private $displayPageMax;

    public function __construct($condition, $displayPageMax = null)
    {
        $this->condition = $condition;
        
        if (isset($displayPageMax)) {
            $this->displayPageMax = $displayPageMax;
        } else {
            $this->displayPageMax = self::DEFAULT_DISPLAY_PAGE_MAX;
        }
    }
    
    public function getCount()
    {
        return $this->condition->getCount();
    }
    public function setCount($count)
    {
        $this->condition->setCount($count);
    }

    public function getLimit()
    {
        return $this->condition->getLimit();
    }
    
    public function setLimit($limit)
    {
        $this->condition->setLimit($limit);
    }

    public function getOffset()
    {
        return $this->condition->getOffset();
    }
    
    public function setOffset($offset)
    {
        $this->condition->setOffset($offset);
    }

    /**
     * 前へのリンクが表示できるかどうかを判定します。
     * @return true or false
     */
    public function isPrev() 
    {
        return 0 < $this->condition->getOffset();
    }

    /**
     * 次へのリンクが表示できるかどうかを判定します。
     * @return true or false
     */
    public function isNext() 
    {
        $count = $this->condition->getCount();
        $nextOffset = $this->condition->getOffset() + $this->condition->getLimit();
        
        return 0 < $count && $nextOffset < $count;
    }

    /**
     * 現在表示中の一覧の最後のoffsetを取得します。
     * @return true or false
     */
    public function getCurrentLastOffset() 
    {
        $count = $this->condition->getCount();
        $nextOffset = $this->getNextOffset($this->condition);
        if ($nextOffset <= 0 || $this->condition->getCount() <= 0) {
            return 0;
        } else {
            return $nextOffset < $count ? $nextOffset - 1 : $count - 1;
        }
    }

    /**
     * 次へリンクのoffsetを返します。
     * @return int next offset
     */
    public function getNextOffset() 
    {
        return $this->condition->getOffset() + $this->condition->getLimit();
    }

    /**
     * 前へリンクのoffsetを返します。
     * @return int preview offset
     */
    public function getPrevOffset() 
    {
        $prevOffset = $this->condition->getOffset() - $this->condition->getLimit();
        return $prevOffset < 0 ? 0 : $prevOffset;
    }
    
    /**
     * 現在ページのインデックスを返します。
     */
    public function getPageIndex() 
    {
        $limit = $this->condition->getLimit();
        $offset = $this->condition->getOffset();
        if ($limit == 0) {
            return 1;
        } else {
            return floor($offset / $limit);
        }
    }

    /**
     * 現在ページのカウント(インデックス+1)を返します。
     */
    public function getPageCount() 
    {
        return $this->getPageIndex() + 1;
    }

    /**
     * 最終ページのインデックスを返します。
     */
    public function getLastPageIndex() 
    {
        $limit = $this->condition->getLimit();
        $count = $this->condition->getCount();
        if ($limit == 0) {
            return 0;
        } else {
            return floor(($count - 1) / $limit);
        }
    }

    /**
     * ページリンクの表示上限を元に、ページ番号リンクの表示開始位置を返します。
     */
    public function getDisplayPageIndexBegin() 
    {
        $lastPageIndex = $this->getLastPageIndex();
        if ( $lastPageIndex < $this->displayPageMax ) {
            return 0;
        } else {
            $currentPageIndex = $this->getPageIndex();
            $displayPageIndexBegin = $currentPageIndex - (floor($this->displayPageMax / 2));
            return $displayPageIndexBegin < 0 ? 0 : $displayPageIndexBegin;
        }
    }

    /**
     * ページリンクの表示上限を元に、ページ番号リンクの表示終了位置を返します。
     */
    public function getDisplayPageIndexEnd() 
    {
        $lastPageIndex = $this->getLastPageIndex();
        $displayPageIndexBegin = $this->getDisplayPageIndexBegin();
        $displayPageRange = $lastPageIndex - $displayPageIndexBegin;
        if ($displayPageRange < $this->displayPageMax) {
            return $lastPageIndex;
        } else {
            return $displayPageIndexBegin + $this->displayPageMax - 1;
        }
    }

    /**
     * ページ番号のリストを返します。
     * @return array page-index-number list
     */
    public function getPageIndexNumbers()
    {
        $pageIndex = array();
        $begin = $this->getDisplayPageIndexBegin();
        $end = $this->getDisplayPageIndexEnd();
        for ($i = $begin; $i <= $end; $i++) {
            $pageIndex[] = $i;
        }
        return $pageIndex;
    }

}

?>