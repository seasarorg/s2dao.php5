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
class S2Dao_PagerView
{
    private $helper = null;
    
    private $href = '';
    private $previewLink = '';
    private $nextLink = '';
    private $pageLinks = '';

    public function __construct(S2Dao_PagerCondition $dto, $href = '')
    {
        $this->href = $href;

        $this->helper = new S2Dao_PagerViewHelper($dto);
        if ($this->helper->isPrev()) {
            $this->makePreviewLink($dto->getLimit());
        } else {
            $this->makeNonePreviewLink();
        }
        if ($this->helper->isNext()) {
            $this->makeNextLink($dto->getLimit());
        } else {
            $this->makeNoneNextLink();
        }
        
        $this->makePageLinks();
    }

    /**
     * 前の○件リンクを生成する
     */
    public function makePreviewLink($limit) 
    {
        if ($limit <= 0) {
            return;
        }
        if (!$this->helper->isPrev()) {
        }
        $this->previewLink = '<a href="' . $this->$href . '">Preview Page</a>';
    }
    
    /**
     * 次の○件リンクを作成する
     */
    public function makeNextLink($limit)
    {
        if ($limit <= 0) {
            return;
        }
        if (!$this->helper->isNext()) {
            $this->makeNoneNextLink($limit);
            return;
        }
        $this->nextLink = '<a href="' . $this->href . '">Next Page</a>';
    }
    
    /**
     * ページリンクを作成する
     */
    public function makePageLinks()
    {
        $begin = $this->helper->getDisplayPageIndexBegin();
        $end = $this->helper->getDisplayPageIndexEnd();
        $index = $this->helper->getPageIndex();

        for ( $i = $begin; $i <= $end; $i++ ) {
            if ($i == $index) {
                $this->pageLinks .= ($i + 1) . ' ';
            } else {
                $this->pageLinks .= '<a href="' . $this->href . '">' . ( $i + 1 ) . '</a> ';
            }
        }
    }

    /**
     * 次の○件が存在しないときの出力を生成する
     */
    public function makeNoneNextLink($limit)
    {
        $this->nextLink = 'Preview Page';
    }
    
    /**
     * 前の○件が存在しないときの出力を生成する
     */
    public function makeNonePreviewLink($limit)
    {
        $this->previewLink = 'Next Page';
    }
    
}

?>