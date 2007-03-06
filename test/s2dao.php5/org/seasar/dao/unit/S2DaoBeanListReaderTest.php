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
class S2DaoBeanListReaderTest extends PHPUnit2_Framework_TestCase {

    private $dataSource = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2DaoBeanListReaderTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dataSource = $container->getComponent("pdo.dataSource");
    }

    protected function tearDown() {
        $this->dataSource = null;
    }
    
    private function getConnection(){
        return $this->dataSource->getConnection();
    }
    
    public function testRead(){
        $emp = new Employee2();
        $emp->setEmpno(7788);
        $emp->setEname("SCOTT");
        $emp->setDeptno(10);
        $dept = new Department2();
        $dept->setDeptno(10);
        $dept->setDname("HOGE");
        $emp->setDepartment($dept);
        $list = new S2Dao_ArrayList();
        $list->add($emp);
        $reader = new S2DaoBeanListReader($list, $this->getConnection());
        $ds = $reader->read();
        $table = $ds->getTable(0);
        $row = $table->getRow(0);
        $this->assertEquals(7788, $row->getValue("EMPNO"));
        $this->assertEquals("SCOTT", $row->getValue("ENAME"));
        $this->assertEquals(10, $row->getValue("DEPTNO"));
        $this->assertEquals("HOGE", $row->getValue("DNAME_0"));
    }
}
?>
