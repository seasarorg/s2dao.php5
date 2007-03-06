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
 * @author nowel
 */
class S2DaoInterceptor3Test extends PHPUnit2_Framework_TestCase {

    private $dao = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2DaoInterceptor3Test");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dao = $container->getComponent("it3.DepartmentAutoDao");
    }

    protected function tearDown() {
        $this->dao = null;
    }

    public function testUpdateTx() {
        $dept = new Department2();
        $dept->setDeptno(10);
        $dept->setVersionNo(0);
        $this->assertEquals(1, $this->dao->update($dept));
    }
    
    public function testDeleteTx() {
        $dept = new Department2();
        $dept->setDeptno(10);
        $dept->setVersionNo(1);
        $this->assertEquals(1, $this->dao->delete($dept));
    }
}
?>
