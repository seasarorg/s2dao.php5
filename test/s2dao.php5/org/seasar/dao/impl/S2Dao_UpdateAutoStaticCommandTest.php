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
// $Id$
//
/**
 * @author nowel
 */
class S2Dao_UpdateAutoStaticCommandTest extends PHPUnit2_Framework_TestCase {

    private $dataSource = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_UpdateAutoStaticCommandTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dataSource = $container->getComponent("pdo.dataSource");
    }

    protected function tearDown() {
        $this->dataSource = null;
    }
    
    private function getDaoClass($class){
        return new ReflectionClass($class);
    }
    
    private function createDaoMetaData(ReflectionClass $daoClass){
        return new S2Dao_DaoMetaDataImpl(
                        $daoClass,
                        $this->dataSource,
                        new S2Dao_BasicStatementFactory(),
                        new S2Dao_BasicResultSetFactory());
    }

    public function testExecuteTx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("update");
        $cmd2 = $dmd->getSqlCommand("getEmployee");
        $emp = $cmd2->execute(array(7788));
        $count = $cmd->execute(array($emp));
        $this->assertEquals(1, $count);
    }

    public function testExecute2Tx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("DepartmentAutoDao"));
        $cmd = $dmd->getSqlCommand("update");
        $dept = new Department2();
        $dept->setDeptno(30);
        $dept->setVersionNo(0);
        $count = $cmd->execute(array($dept));
        $this->assertEquals(1, $count);
        $this->assertEquals(1, $dept->getVersionNo());
    }

    public function testExecute3Tx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("DepartmentAutoDao"));
        $cmd = $dmd->getSqlCommand("update");
        $dept = new Department2();
        $dept->setDeptno(10);
        $dept->setVersionNo(-1);
        try {
            $cmd->execute(array($dept));
            $this->fail("1");
        } catch (S2Dao_UpdateFailureRuntimeException $ex) {
            echo $ex->getMessage();
        }
    }

    public function testExecute4Tx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("update2");
        $cmd2 = $dmd->getSqlCommand("getEmployee");
        $emp = $cmd2->execute(array(7788));
        $count = $cmd->execute(array($emp));
        $this->assertEquals(1, $count);
    }

    public function testExecute5Tx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("update3");
        $cmd2 = $dmd->getSqlCommand("getEmployee");
        $emp = $cmd2->execute(array(7788));
        $count = $cmd->execute(array($emp));
        $this->assertEquals(1, $count);
    }
}
?>
