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
// $Id$
//

/**
 * @author yonekawa
 */
class S2Dao_PagerYamlResultSetWrapperTest extends PHPUnit2_Framework_TestCase {

    private $wrapper = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PagerYamlResultSetWrapperTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->wrapper = new S2Dao_PagerYamlResultSetWrapper();
    }

    protected function tearDown() {
        $this->wrapper = null;
    }

    public function testFilter() {
        $yaml = '';
        $filter_yaml = '';

        $this->condition->setLimit(3);
        $this->condition->setOffset(2);

        $yaml = S2Dao_PagerUtil::filterYaml($yaml, $this->condition);

        $this->assertEquals($yaml, $filter_yaml);
    }
}

?>