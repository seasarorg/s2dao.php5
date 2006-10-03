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
class S2Dao_PagerObject
{
    private $status = null;
    private $data = null;
    private $hasPrev = false;
    private $hasNext = false;
    private $pageIndex = 0;
    private $isFirst = 0;
    private $isLast = 0;

    public function __construct($resultSet, $condition)
    {
        $this->data = $resultSet;
        $this->status = $condition;

        $helper = new S2Dao_PagerViewHelper($condition);
        
        $this->hasPrev = $helper->isPrev();
        $this->hasNext = $helper->isNext();
        $this->currentIndex = $helper->getPageIndex();
        $this->isFirst = $helper->getDisplayPageIndexBegin() == $this->currentIndex ? true : false;
        $this->isLast = $helper->getDisplayPageIndexEnd() == $this->currentIndex ? true : false;
    }    
}

?>