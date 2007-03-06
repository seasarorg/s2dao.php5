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
class S2Dao_BeanListMetaDataResultSetHandlerTest extends PHPUnit2_Framework_TestCase {
    
    private $dataSource = null;
    private $bmd = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_BeanListMetaDataResultSetHandlerTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dataSource = $container->getComponent("pdo.dataSource");
        $this->bmd = $this->createBeanMetaData(new ReflectionClass("Employee2"));
    }

    protected function tearDown() {
        $this->dataSource = null;
        $this->bmd = null;
    }

    public function testHandle() {
        $handler = new S2Dao_BeanListMetaDataResultSetHandler($this->bmd);
        $sql = "select * from EMP2 emp2";
        $conn = $this->getConnection();
        $ps = $conn->prepare($sql);
        $ps->execute();
        $ret = $handler->handle($ps);
        $this->assertNotNull($ret);
        for ($i = 0; $i < count($ret); ++$i) {
            $emp = $ret[$i];
            var_dump($emp->getEmpno() . "," . $emp->getEname());
        }
    }
    
    public function testHandle2() {
        $handler = new S2Dao_BeanListMetaDataResultSetHandler($this->bmd);
        $sql = "select emp2.*, dept2.dname as dname_0 from EMP2 emp2, DEPT2 dept2 where emp2.deptno = dept2.deptno and emp2.deptno = 20";
        $conn = $this->getConnection();
        $ps = $conn->prepare($sql);
        $ps->execute();
        $ret = $handler->handle($ps);
        $this->assertNotNull($ret);
        for ($i = 0; $i < $ret->size(); ++$i) {
            $emp = $ret->get($i);
            var_dump($emp);
            $dept = $emp->getDepartment();
            $this->assertNotNull($dept);
            $this->assertEquals($emp->getDeptno(), $dept->getDeptno());
            $this->assertNotNull($dept);
        }
    }

    public function testHandle3() {
        $handler = new S2Dao_BeanListMetaDataResultSetHandler($this->bmd);
        $sql = "select emp2.*, dept2.deptno as deptno_0, dept2.dname as dname_0 from EMP2 emp2, DEPT2 dept2 where dept2.deptno = 20 and emp2.deptno = dept2.deptno";
        $conn = $this->getConnection();
        $ps = $conn->prepare($sql);
        $ps->execute();
        $ret = $handler->handle($ps);
        $emp = $ret->get(0);
        $emp2 = $ret->get(1);
        $this->assertSame($emp->getDepartment(), $emp2->getDepartment());
    }

    private function getConnection(){
        return $this->dataSource->getConnection();
    }
    
    private function createBeanMetaData(ReflectionClass $class){
        $conn = $this->getConnection();
        $dbms = new S2Dao_SQLite();
        return new S2Dao_BeanMetaDataImpl($class, $conn, $dbms);
    }
}
?>
