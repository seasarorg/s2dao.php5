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
class S2Dao_InsertAutoDynamicCommandTest extends PHPUnit2_Framework_TestCase {

    private $dataSource = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_InsertAutoDynamicCommandTest");
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
        $cmd = $dmd->getSqlCommand("insert");
        $this->assertTrue($cmd instanceof S2Dao_InsertAutoStaticCommand);
        $emp = new Employee2();
        $emp->setEmpno(991);
        $emp->setEname("hoge");
        $count = $cmd->execute(array($emp));
        $this->assertEquals(1, $count);
    }

    public function testExecute2Tx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("IdentityTableAutoDao"));
        $cmd = $dmd->getSqlCommand("insert");
        $table = new IdentityTable();
        $table->setIdName("hoge");
        $count1 = $cmd->execute(array($table));
        $this->assertEquals(1, $count1);
        $id1 = $table->getMyid();
        var_dump($id1);
        $count2 = $cmd->execute(array($table));
        $this->assertEquals(1, $count2);
        $id2 = $table->getMyid();
        var_dump($id2);

        $this->assertEquals(1, $id2 - $id1);
    }

    public function testExecute3_1Tx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("SeqTableAutoDao"));
        $cmd = $dmd->getSqlCommand("insert");
        $table1 = new SeqTable();
        $table1->setName("hoge");
        $count = $cmd->execute(array($table1));
        $this->assertEquals(1, $count);
        var_dump($table1->getId());
        $this->assertTrue($table1->getId() > 0);
    }

    public function testExecute3_2Tx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("SeqTableAuto2Dao"));
        $cmd = $dmd->getSqlCommand("insert");
        $table1 = new SeqTable2();
        $table1->setName("hoge");
        $count = $cmd->execute(array($table1));
        $this->assertEquals(1, $count);
        var_dump($table1->getId());
        $this->assertTrue((int)$table1->getId() > 0);

        $table2 = new SeqTable2();
        $table2->setName("foo");
        $cmd->execute(array($table2));
        var_dump($table2->getId());
        $this->assertEquals((int)$table2->getId() > (int)$table1->getId());
    }

    public function testExecute4Tx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("insert2");
        $emp = new Employee2();
        $emp->setEmpno(992);
        $emp->setEname("hoge");
        $count = $cmd->execute(array($emp));
        $this->assertEquals(1, $count);
    }

    public function testExecute5Tx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("insert3");
        $emp = new Employee2();
        $emp->setEmpno(993);
        $emp->setEname("hoge");
        $emp->setDeptno(10);
        $count = $cmd->execute(array($emp));
        $this->assertEquals(1, $count);
    }
}
?>
