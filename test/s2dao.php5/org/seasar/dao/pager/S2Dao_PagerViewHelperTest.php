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
// | Authors: yonekawa                                                       |
// +----------------------------------------------------------------------+
// $Id: $
//
/**
 * @author yonekawa
 */
class S2Dao_PagerViewHelperTest extends PHPUnit2_Framework_TestCase {

    private $helper_ = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PagerViewHelperTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $dto = new S2Dao_DefaultPagerCondition();
        $dto->setCount(50);
        $dto->setOffset(5);
        $dto->setLimit(10);
        
        $this->helper_ = new S2Dao_PagerViewHelper($dto);
    }

    protected function tearDown() {
        $this->helper_ = null;
    }

    public function testGetCount() {
        $this->assertEquals($this->helper_->getCount(), 50);
    }

    public function testSetCount() {
        $this->helper_->setCount(100);
        $this->assertEquals($this->helper_->getCount(), 100);
    }

    public function testGetLimit() {
        $this->assertEquals($this->helper_->getLimit(), 10);
    }

    public function testSetLimit() {
        $this->helper_->setLimit(20);
        $this->assertEquals($this->helper_->getLimit(), 20);
    }

    public function testGetOffset() {
        $this->assertEquals($this->helper_->getOffset(), 5);
    }

    public function testSetOffset() {
        $this->helper_->setOffset(3);
        $this->assertEquals($this->helper_->getOffset(), 3);
    }

    public function testIsPrevTrue() {
        $this->assertEquals($this->helper_->isPrev(), true);
    }
    
    public function testIsPrevFalse() {
        $this->helper_->setOffset(0);
        $this->assertEquals($this->helper_->isPrev(), false);
    }

    public function testIsNextTrue() {
        $this->assertEquals($this->helper_->isNext(), true);
    }

    public function testIsNextFalse() {
        $lastIndex = $this->helper_->getCount() - 1;
        $this->helper_->setOffset($lastIndex);
        $this->assertEquals($this->helper_->isNext(), false);
    }

    public function testGetCurrentLastOffset() {
        $this->assertEquals($this->helper_->getCurrentLastOffset(), 14);
    }

    public function testGetCurrentLastOffsetNextOffsetNotExists() {
        $this->helper_->setOffset($this->helper_->getCount() - 1);
        $this->assertEquals($this->helper_->getCurrentLastOffset(), $this->helper_->getCount() - 1);
    }

    public function testGetNextOffset() {
        $this->assertEquals($this->helper_->getNextOffset(), 15);
    }

    public function testGetPrevOffset() {
        $this->helper_->setOffset(10);
        $this->helper_->setLimit(5);
        $this->assertEquals($this->helper_->getPrevOffset(), 5);
    }
    public function testGetPrevOffsetNotExists() {
        $this->assertEquals($this->helper_->getPrevOffset(), 0);
    }

    public function testGetPageIndex() {
        $this->helper_->setOffset(10);
        $this->assertEquals((int)$this->helper_->getPageIndex(), 1);
    }

    public function testGetPageCount() {
        $this->helper_->setOffset(10);
        $this->assertEquals((int)$this->helper_->getPageCount(), 2);
    }

    public function testGetLastPageIndex() {
        $this->assertEquals((int)$this->helper_->getLastPageIndex(), 4);
    }

    public function testGetDisplayPageIndexBegin() {
        $this->helper_->setCount(20);
        $this->helper_->setOffset(5);
        $this->helper_->setLimit(1);
        $this->assertEquals((int)$this->helper_->getDisplayPageIndexBegin(), 1);
    }

    public function testGetDisplayPageIndexEnd() {
        $this->helper_->setCount(150);
        $this->assertEquals($this->helper_->getDisplayPageIndexEnd(), 8);
    }

    public function testGetPageIndexNumbers() {
        $list = array(0, 1, 2, 3, 4, 5);
        $this->helper_->setLimit(25);
        $this->helper_->setCount(150);
        $this->assertEquals($this->helper_->getPageIndexNumbers(), $list);
    }
}

?>