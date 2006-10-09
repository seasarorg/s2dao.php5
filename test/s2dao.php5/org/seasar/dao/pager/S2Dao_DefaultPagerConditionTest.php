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
class S2Dao_DefaultPagerConditionTest extends PHPUnit2_Framework_TestCase {

    private $condition_;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_DefaultPagerConditionTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->condition_ = new S2Dao_DefaultPagerCondition();
        $this->condition_->setOffset(5);
        $this->condition_->setLimit(10);
        $this->condition_->setCount(100);
    }

    protected function tearDown() {
        $this->condition_ = null;
    }

    /**
     * @todo Implement testGetCount().
     */
    public function testGetCount() {
        $this->assertEquals($this->condition_->getCount(), 100);
    }

    public function testSetCount() {
        $this->condition_->setCount(50);
        $this->assertEquals($this->condition_->getCount(), 50);
    }

    public function testGetLimit() {
        $this->assertEquals($this->condition_->getLimit(), 10);
    }

    public function testSetLimit() {
        $this->condition_->setLimit(20);
        $this->assertEquals($this->condition_->getLimit(), 20);
    }

    public function testGetOffset() {
        $this->assertEquals($this->condition_->getOffset(), 5);
    }

    public function testSetOffset() {
        $this->condition_->setOffset(3);
        $this->assertEquals($this->condition_->getOffset(), 3);
    }
}

?>
