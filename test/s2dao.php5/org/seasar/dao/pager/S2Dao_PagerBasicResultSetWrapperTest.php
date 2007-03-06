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
class S2Dao_PagerBasicResultSetWrapperTest extends PHPUnit2_Framework_TestCase {

    private $wrapper = null;
    private $condition = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PagerResultSetWrapperImplTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->wrapper = new S2Dao_PagerBasicResultSetWrapper();
        $this->condition = new S2Dao_DefaultPagerCondition();
    }

    protected function tearDown() {
        $this->wrapper = null;
        $this->condition = null;
    }

    public function testFilter() {
        $array = array('aaa', 'bbb', 'ccc', 'ddd', 'eee');
        $filter_array = array('ccc', 'ddd', 'eee');
        
        $this->condition->setLimit(3);
        $this->condition->setOffset(2);
        
        $array = $this->wrapper->filter($array, $this->condition);
        
        $this->assertEquals($array, $filter_array);
    }
    
    function testFilterS2Dao_ArrayList() {
        $array_data = array('aaa', 'bbb', 'ccc', 'ddd', 'eee');
        $filter_array_data = array('ccc', 'ddd', 'eee');
        $list = new S2Dao_ArrayList(new ArrayObject($array_data));
        $filter_list = new S2Dao_ArrayList(new ArrayObject($filter_array_data));
        
        $this->condition->setLimit(3);
        $this->condition->setOffset(2);
        
        $list = $this->wrapper->filter($list, $this->condition);
        
        $this->assertEquals($list, $filter_list);
    }
     
}

?>