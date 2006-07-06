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
class S2DaoInterceptorTest extends PHPUnit2_Framework_TestCase {

    private $dao = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2DaoInterceptorTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dao = $container->getComponent("it.Employee2DaoImpl");
    }

    protected function tearDown() {
        $this->dao = null;
    }

    public function testSelectBeanList() {
        $employees = $this->dao->getAllEmployeesList();
        for ($i = 0; $i < $employees->size(); ++$i) {
            var_dump($employees->get($i));
        }
        $this->assertEquals(true, $employees->size() > 0);
    }
    
    public function testSelectBean() {
        $employee = $this->dao->getEmployee(7788);
        var_dump($employee);
        $this->assertEquals("SCOTT", $employee->getEname());
    }
    
    public function testSelectObject() {
        $count = $this->dao->getCount();
        var_dump("count:" . $count);
        $this->assertEquals(true, $count > 0);
    }

    public function testUpdateTx() {
        $employee = $this->dao->getEmployee(7788);
        $this->assertEquals(1, $this->dao->update($employee));
    }

//    public function testEntityManager() {
//        // S2Container.PHP5 not supporte 'abstract class'
//        $employees = $this->dao->getEmployeesByDeptnoArray(10);
//        var_dump($employees);
//        $this->assertTrue(is_array($employees));
//        $this->assertEquals(3, count($employees));
//    }
    
    public function testInsertTx() {
        $this->dao->insert(9999, "hoge");
    }

}
?>
