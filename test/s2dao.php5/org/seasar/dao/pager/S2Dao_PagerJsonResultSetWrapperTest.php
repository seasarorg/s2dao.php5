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
// $Id: $
//
/**
 * @author yonekawa
 */
class S2Dao_PagerJsonResultSetWrapperTest extends PHPUnit2_Framework_TestCase {

    private $wrapper = null;
    private $condition;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PagerJsonResultSetWrapperTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->wrapper = new S2Dao_PagerJsonResultSetWrapper();
        $this->condition = new S2Dao_DefaultPagerCondition();
    }

    protected function tearDown() {
        $this->wrapper = null;
        $this->condition = null;
    }

    public function testFilter() {
        $array1 = array("aaa", "bbb", "ccc", "ddd", "eee");
        $array2 = array("ccc", "ddd", "eee");
        $json = json_encode($array1);
        $filter_json = json_encode($array2);
        
        $this->condition->setLimit(3);
        $this->condition->setOffset(2);

        $json = $this->wrapper->filter($json, $this->condition);

        $this->assertEquals($json, $filter_json);
    }
}

?>
