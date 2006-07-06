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
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id$
//
/**
 * @author yonekawa
 */
class S2Dao_PagerS2DaoInterceptorWrapperTest extends PHPUnit2_Framework_TestCase {

    private $dao;
    private $dto;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PagerS2DaoInterceptorWrapperTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dao = $container->getComponent("pager.Employee2DaoImpl");
        $this->dto = new S2Dao_DefaultPagerCondition();
    }

    protected function tearDown() {
        $this->dao = null;
    }

    public function testSelectLimit5() {
        $this->dto->setLimit(5);
        $emp = $this->dao->getEmployeeByPagerConditionList($this->dto);
        $this->assertEquals(count($emp), 5);
    }

    public function testSelectLimit5Offset2() {
        $this->dto->setLimit(5);
        $this->dto->setOffset(2);
        $empPager = $this->dao->getEmployeeByPagerConditionList($this->dto);
        $empOrigin = $this->dao->getAllEmployeesList();
        
        $this->assertEquals(count($empPager), 5);
        
        $empPager = $empPager[0];
        $empOrigin = $empOrigin[2];
        $this->assertEquals($empPager->getEmpno(), $empOrigin->getEmpno());
    }

    public function testSettingCount()
    {
        $this->dto->setLimit(5);
        $this->dto->setOffset(10);

        $empPager = $this->dao->getEmployeeByPagerConditionList($this->dto);
        $empOriginCount = $this->dao->getCount();
        
        $this->assertEquals($empOriginCount, $this->dto->getCount());
    }

}

?>