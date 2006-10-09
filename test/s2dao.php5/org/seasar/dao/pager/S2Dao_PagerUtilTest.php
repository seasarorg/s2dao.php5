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
class S2Dao_PagerUtilTest extends PHPUnit2_Framework_TestCase {

    private $condition = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PagerUtilTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->condition = new S2Dao_DefaultPagerCondition();
    }

    protected function tearDown() {
        $this->condition = null;
    }

    public function testFilter() {
        $array = array('aaa', 'bbb', 'ccc', 'ddd', 'eee');
        $filter_array = array('ccc', 'ddd', 'eee');
        
        $this->condition->setLimit(3);
        $this->condition->setOffset(2);
        
        $array = S2Dao_PagerUtil::filter($array, $this->condition);
        
        $this->assertEquals($array, $filter_array);
    }

    public function testFilterS2Dao_ArrayList() {
        $array_data = array('aaa', 'bbb', 'ccc', 'ddd', 'eee');
        $filter_array_data = array('ccc', 'ddd', 'eee');
        $list = new S2Dao_ArrayList(new ArrayObject($array_data));
        $filter_list = new S2Dao_ArrayList(new ArrayObject($filter_array_data));

        $this->condition->setLimit(3);
        $this->condition->setOffset(2);
        
        $list = S2Dao_PagerUtil::filter($list, $this->condition);
        
        $this->assertEquals($list, $filter_list);
    }

    public function testFilterJson() {
        $array1 = array("aaa", "bbb", "ccc", "ddd", "eee");
        $array2 = array("ccc", "ddd", "eee");
        
        $this->condition->setLimit(3);
        $this->condition->setOffset(2);

        $json = json_encode($array1);
        $filter_json = json_encode($array2);

        $json = S2Dao_PagerUtil::filterJson($json, $this->condition);

        $this->assertEquals($json, $filter_json);
    }

    public function testFilterYaml() {
        $array1 = array("aaa", "bbb", "ccc", "ddd", "eee");
        $array2 = array("ccc", "ddd", "eee");

        $this->condition->setLimit(3);
        $this->condition->setOffset(2);
        
        $spyc = new Spyc();

        $yaml = $spyc->YAMLLoad($array1);
        $filter_yaml = $spyc->YAMLLoad($array2);

        $yaml = S2Dao_PagerUtil::filterYaml($yaml, $this->condition);

        $this->assertEquals($yaml, $filter_yaml);
    }
    
    public function testCreatePagerObject() {
        $data = array('a' => '1', 'b' => '2', 'c' => '3');
        
        $this->condition->setLimit(3);
        $this->condition->setOffset(2);

        $pager = $this->createPagerObject($data);        
        $pagerObject = S2Dao_PagerUtil::createPagerObject($data, $this->condition);
        
        $this->assertEquals($pager, $pagerObject);
    }

    public function testCreatePagerListObject() {
        $data = array('a' => '1', 'b' => '2', 'c' => '3');
        $data = new S2Dao_ArrayList(new ArrayObject($data));
        
        $this->condition->setLimit(3);
        $this->condition->setOffset(2);

        $pager = $this->createPagerObject($data);        
        $pagerObject = S2Dao_PagerUtil::createPagerObject($data, $this->condition);
        
        $this->assertEquals($pager, $pagerObject);
    }

    public function testCreatePagerJsonObject() {
        $data = array('a' => '1', 'b' => '2', 'c' => '3');
        
        $this->condition->setLimit(3);
        $this->condition->setOffset(2);

        $pager = $this->createPagerObject($data);
        $pager = json_encode($pager);
        $pagerObject = S2Dao_PagerUtil::createPagerJsonObject(json_encode($data), $this->condition);
        
        $this->assertEquals($pager, $pagerObject);
    }
    
    public function testCreatePagerYamlObject() {
        $spyc = new Spyc();
        $data = array('a' => '1', 'b' => '2', 'c' => '3');
        
        $this->condition->setLimit(3);
        $this->condition->setOffset(2);

        $pager = $this->createPagerObject($data);
        $pager = $spyc->YAMLDump($pager);
        $pagerObject = S2Dao_PagerUtil::createPagerYamlObject($spyc->YAMLDump($data), $this->condition);
        
        $this->assertEquals($pager, $pagerObject);
    }

    private function createPagerObject($data) {
        $helper = new S2Dao_PagerViewHelper($this->condition);
        $pager = array();
        $pager['data'] = $data;
        $pager['status'] = array(
            'count' => $this->condition->getCount(),
            'limit' => $this->condition->getLimit(),
            'offset' => $this->condition->getOffset()
        );
        $pager['hasPrev'] = $helper->isPrev();
        $pager['hasNext'] = $helper->isNext();
        $pager['currentIndex'] = $helper->getPageIndex();
        $pager['isFirst'] = $helper->getDisplayPageIndexBegin() == $pager['currentIndex'] ? true : false;
        $pager['isLast'] = $helper->getDisplayPageIndexEnd() == $pager['currentIndex'] ? true : false;

        return $pager;
    }
}

?>
