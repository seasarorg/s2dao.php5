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
// | Authors: yonekawa                                                       |
// +----------------------------------------------------------------------+
// $Id$
//
/**
 * @author yonekawa
 */
class S2Dao_PagerSupportTest extends PHPUnit2_Framework_TestCase {

    private $pager_;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PagerSupportTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->pager_ = new S2Dao_PagerSupport(10, "S2Dao_DefaultPagerCondition", "default");
    }

    protected function tearDown() {
        $this->pager_ = null;
    }

    public function testGetPagerCondition() {
        $dto = $this->pager_->getPagerCondition();
        
        $isPagerCondition = ($dto instanceof S2Dao_DefaultPagerCondition);
        $this->assertEquals($isPagerCondition, true);

        $this->assertEquals($dto->getLimit(), 10);
        $this->assertEquals($dto->getOffset(), 0);
        $this->assertEquals($dto->getCount(), 0);
    }
    
    public function testUpdateOffset() {
        $this->pager_->updateOffset(20);
        $dto = $this->pager_->getPagerCondition();
        $this->assertEquals($dto->getOffset(), 20);
        
    }

    public function testGetPagerConditionAfter() {
        $dto = $this->pager_->getPagerCondition();

        $isPagerCondition = ($dto instanceof S2Dao_DefaultPagerCondition);
        $this->assertEquals($isPagerCondition, true);

        $this->assertEquals($dto->getLimit(), 10);
        $this->assertEquals($dto->getOffset(), 20);
        $this->assertEquals($dto->getCount(), 0);
    }
}

?>