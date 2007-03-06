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
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id: $
//
/**
 * @author yonekawa
 */
class S2Dao_PagerS2DaoInterceptorWrapperLimitOffsetQueryTest extends PHPUnit2_Framework_TestCase {
 
    private $dao = null;
    private $dto = null;
    private $limit = S2Dao_PagerCondition::NONE_LIMIT;
    private $offset = 0;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PagerS2DaoInterceptorWrapperLimitOffsetQueryTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dao = $container->getComponent("pager.empPagerLimitOffsetQuery");
        $this->dto = new S2Dao_DefaultPagerCondition();

        $dataSource = $container->getComponent("pdo.dataSource");
        $dbms = S2DaoDbmsManager::getDbms($dataSource->getConnection());
        if ($dbms->usableLimitOffsetQuery()) {
            $this->limit = 5;
            $this->offset = 2;
        }
        $this->dto->setLimit($this->limit);
        $this->dto->setOffset($this->offset);
        
    }

    protected function tearDown() {
        $this->dao = null;
        $this->dto = null;
    }

    public function testSelectLimit() {
        $empPager = $this->dao->getAllByPagerConditionList($this->dto);
        if ($this->limit < 0) {
            $empOrigin = $this->dao->getAllEmployeesList();
            $this->limit = count($empOrigin);
        }
        $this->assertEquals(count($empPager), $this->limit);
    }

    public function testSelectLimit5Offset2() {
        $empPager = $this->dao->getAllByPagerConditionList($this->dto);
        $empOrigin = $this->dao->getAllEmployeesList();
        
        if ($this->limit < 0) {
            $this->limit = count($empOrigin);
        }
       
        $this->assertEquals(count($empPager), $this->limit);
        
        $empPager = $empPager[0];
        $empOrigin = $empOrigin[$this->offset];
        $this->assertEquals($empPager->getEmpno(), $empOrigin->getEmpno());
    }

    public function testSettingCount()
    {
        $empPager = $this->dao->getAllByPagerConditionList($this->dto);
        $empOriginCount = $this->dao->getCount();        
        $this->assertEquals($empOriginCount, $this->dto->getCount());
    }
}

?>