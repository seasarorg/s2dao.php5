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
class S2Dao_PagerFilterTest extends PHPUnit2_Framework_TestCase {

    private $dao;
    private $dao2;
    private $dto;
    private $helper;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PagerFilterTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dao = $container->getComponent("pager.empPager");
        $this->dto = new S2Dao_DefaultPagerCondition();
        $this->dto->setLimit(5);
        $this->dto->setOffset(2);
        $this->helper = new S2Dao_PagerViewHelper($this->dto);
    }

    protected function tearDown() {
        $this->dao = null;
        $this->dao2 = null;
        $this->dto = null;
        $this->helper = null;
    }

    public function testFilterResultSetList() {
        $result = $this->dao->getAllByPagerConditionList($this->dto);
        
        $filterObject = $this->createFilterObject($result);

        $filter_result = $this->dao->getAllByPagerConditionFilterList($this->dto);

        $this->assertEquals($filterObject, $filter_result);
    }

    public function testFilterResultSetArray() {
        $result = $this->dao->getAllByPagerConditionArray($this->dto);
        
        $filterObject = $this->createFilterObject($result);
        
        $filter_result = $this->dao->getAllByPagerConditionFilterArray($this->dto);

        $this->assertEquals($filterObject, $filter_result);
    }

    public function testFilterResultSetJson() {
        $result = $this->dao->getAllByPagerConditionJson($this->dto);

        $filterObject = $this->createFilterObject(json_decode($result));
        $filterObject = json_encode($filterObject);

        $filter_result = $this->dao->getAllByPagerConditionFilterJson($this->dto);

        $this->assertEquals($filterObject, $filter_result);
    }

    public function testFilterResultSetYaml() {
        $spyc = new Spyc();
        $result = $this->dao->getAllByPagerConditionYaml($this->dto);

        $filterObject = $this->createFilterObject($spyc->YAMLLoad($result));
        $filterObject = $spyc->YAMLDump($filterObject);

        $filter_result = $this->dao->getAllByPagerConditionFilterYaml($this->dto);
        
        $this->assertEquals($filterObject, $filter_result);
    }

    private function createFilterObject($result)
    {
        $filterObject = array();
        $filterObject['data'] = $result;
        $filterObject['status'] = array(
            'count' => $this->dto->getCount(),
            'limit' => $this->dto->getLimit(),
            'offset' => $this->dto->getOffset()
        );
        $filterObject['hasPrev'] = $this->helper->isPrev();
        $filterObject['hasNext'] = $this->helper->isNext();
        $filterObject['currentIndex'] = $this->helper->getPageIndex();
        $filterObject['isFirst'] = $this->helper->getDisplayPageIndexBegin() == $filterObject['currentIndex'] ? true : false;
        $filterObject['isLast'] = $this->helper->getDisplayPageIndexEnd() == $filterObject['currentIndex'] ? true : false;

        return $filterObject;
    }
}

?>