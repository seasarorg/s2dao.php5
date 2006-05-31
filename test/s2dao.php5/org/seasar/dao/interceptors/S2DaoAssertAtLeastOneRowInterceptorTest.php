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
 * @author nowel
 */
class S2DaoAssertAtLeastOneRowInterceptorTest extends PHPUnit2_Framework_TestCase {

    private $employeeDao = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2DaoAssertAtLeastOneRowInterceptorTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->employeeDao = $container->getComponent("atleast.Employee2Dao");
    }

    protected function tearDown() {
        $this->employeeDao = null;
    }

    public function testMoreThanOneRowTx(){
        $ret = $this->employeeDao->updateSal("A%");
        $this->assertEquals(2, $ret);
    }

    public function testNoRowTx() {
        try {
            $ret = $this->employeeDao->updateSal("ZZ%");
            $this->fail("count: " . $ret);
        } catch (S2Dao_NoRowsUpdatedRuntimeException $e) {
            $this->assertTrue(preg_match("/No rows were updated/", $e->getMessage()) == 1);
        }
    }
}
?>
