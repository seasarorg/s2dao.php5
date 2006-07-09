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
class S2Dao_DaoMetaDataImplTest extends PHPUnit2_Framework_TestCase {

    private $dataSource = null;
    private $statementFactory = null;
    private $resultSetFactory = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_DaoMetaDataImplTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dataSource = $container->getComponent("pdo.dataSource");
        $this->statementFactory = new S2Dao_BasicStatementFactory();
        $this->resultSetFactory = new S2Dao_BasicResultSetFactory();
    }

    protected function tearDown() {
        $this->dataSource = null;
        $this->statementFactory = null;
        $this->resultSetFactory = null;
    }
    
    private function getDaoClass($class){
        return new ReflectionClass($class);
    }
    
    private function getBean($class){
        return new $class;
    }
    
    private function getBeanClass($class){
        return $this->getBean($class);
    }
    
    private function createDaoMetaData(ReflectionClass $daoClass){
        return new S2Dao_DaoMetaDataImpl(
                        $daoClass,
                        $this->dataSource,
                        $this->statementFactory,
                        $this->resultSetFactory);
    }
    
    private function getAnnotationReaderFactory(){
        return new S2Dao_FieldAnnotationReaderFactory();
    }

    private function setProperty($obj, $name, $value) {
        $objClass = new ReflectionClass($obj);
        $desc = S2Container_BeanDescFactory::getBeanDesc($objClass);
        $propertyDesc = $desc->getPropertyDesc($name);
        $propertyDesc->setValue($obj, $value);
    }

    private function getProperty($obj, $name) {
        $objClass = new ReflectionClass($obj);
        $desc = S2Container_BeanDescFactory::getBeanDesc($objClass);
        $propertyDesc = $desc->getPropertyDesc($name);
        return $propertyDesc->getValue($obj);
    }

    public function testSelectBeanList() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee2Dao"));
        $cmd = $dmd->getSqlCommand("getAllEmployeesList");
        $this->assertNotNull($cmd);
        $this->assertTrue(stripos($cmd->getSql(), "SELECT * FROM emp2") > 0 );
        $rsh = $cmd->getResultSetHandler();
        $this->assertEquals($this->getBeanClass("Employee2"),
                            $rsh.getBeanMetaData()->getBeanClass());
    }

    public function testSelectBeanArray() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee2Dao"));
        $cmd = $dmd->getSqlCommand("getAllEmployeesArray");
        $this->assertNotNull($cmd);
        $rsh = $cmd->getResultSetHandler();
        $this->assertTrue($this->getBeanClass("Employee2") == $rsh->getBeanMetaData()->getBeanClass());
    }

    public function testSelectBean() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee2Dao"));
        $cmd = $dmd->getSqlCommand("getEmployee");
        $this->assertNotNull($cmd);
        $this->assertEquals("S2Dao_BeanMetaDataResultSetHandler",
                            get_class($cmd->getResultSetHandler()));
        $empno = $cmd->getArgNames();
        $this->assertEquals("empno", $empno[0]);
    }

    public function testSelectObject() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee2Dao"));
        $cmd = $dmd->getSqlCommand("getCount");
        $this->assertNotNull($cmd);
        $this->assertEquals("S2Dao_BeanMetaDataResultSetHandler",
                            get_class($cmd->getResultSetHandler()));
        $this->assertTrue(strcasecmp("SELECT COUNT(*) FROM emp2", $cmd->getSql()) == 0);
    }

    public function testUpdate() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee2Dao"));
        $cmd = $dmd->getSqlCommand("update");
        $this->assertNotNull($cmd);
        $args = $cmd->getArgNames();
        $this->assertEquals("employee", $args[0]);
    }

    public function testInsertAutoTx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("insert");
        $this->assertNotNull($cmd);
        $emp = $this->getBean("Employee2");
        $this->setProperty($emp, "empno", 991);
        $this->setProperty($emp, "ename", "hoge");
        $cmd->execute(array($emp));
    }

    public function testUpdateAutoTx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("update");
        $this->assertNotNull($cmd);
        $cmd2 = $dmd->getSqlCommand("getEmployee");
        $emp = $cmd2->execute(array(991));
        $this->setProperty($emp, "ename", "hoge2");
        $cmd->execute(array($emp));
    }

    public function testDeleteAutoTx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("delete");
        $this->assertNotNull($cmd);
        $cmd2 = $dmd->getSqlCommand("getEmployee");
        $emp = $cmd2->execute(array(991));
        $cmd->execute(array($emp));
    }
    
    public function testIllegalAutoUpdateMethod() {
        try {
            $this->createDaoMetaData($this->getDaoClass("IllegalEmployeeAutoDao"));
            $this->fail("1");
        } catch (S2Dao_IllegalSignatureRuntimeException $ex) {
            echo $ex->getSignature();
        }
    }

    public function testSelectAuto() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("getEmployeeByDeptnoList");
        echo $cmd->getSql();
    }
    
//    public function testCreateFindCommand() {
//        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
//        $cmd = $dmd->createFindCommand(null);
//        $employees = $cmd->execute(null);
//        var_dump($employees);
//        $this->assertTrue($employees->size() > 0);
//    }
//
//    public function testCreateFindCommand2() {
//        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
//        $cmd = $dmd->createFindCommand(null);
//        $employees = $cmd->execute(null);
//        var_dump($employees);
//        $this->assertTrue($employees->size() > 0);
//    }
//
//    public function testCreateFindCommand3() {
//        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
//        $cmd = $dmd->createFindCommand("select * from emp");
//        $employees = $cmd->execute(null);
//        var_dump($employees);
//        $this->assertTrue($employees->size() > 0);
//    }
//
//    public function testCreateFindCommand4() {
//        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
//        $cmd = $dmd->createFindCommand("order by empno");
//        $employees = $cmd->execute(null);
//        var_dump($employees);
//        $this->assertTrue($employees->size() > 0);
//    }
//
    public function testCreateFindCommand5() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $dmd->setDbms(new S2Dao_SQLite());
        $cmd = $dmd->createFindCommand("empno = ?");
        echo $cmd->getSql();
        $this->assertTrue(preg_match("/ AND empno = \?$/", $cmd->getSql()) == 1);
    }

    public function testCreateFindBeanCommand() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->createFindBeanCommand("empno = ?");
        $employee = $cmd->execute(array(7788));
        var_dump($employee);
        $this->assertNotNull($employee);
    }

    public function testCreateObjectBeanCommand() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->createFindObjectCommand("select count(*) from emp2");
        $count = $cmd->execute(null);
        $this->assertEquals(17, (int)$count);
    }

    public function testSelectAutoByQuery() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("getEmployeesBySalList");
        $employees =  $cmd->execute(array(0, 1000));
        var_dump($employees);
        $this->assertEquals(2, $employees->size());
    }

    public function testSelectAutoByQueryMultiIn() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("getEmployeesByEnameJobList");
        echo $cmd->getSql();
        $enames = new S2Dao_ArrayList();
        $enames->add("SCOTT");
        $enames->add("MARY");
        $jobs = new S2Dao_ArrayList();
        $jobs->add("ANALYST");
        $jobs->add("FREE");
        $employees =  $cmd->execute(array($enames, $jobs));
        var_dump($employees);
    }

    public function testRelation() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee2Dao"));
        $cmd = $dmd->getSqlCommand("getAllEmployeesList");
        $emps =  $cmd->execute(null);
        var_dump($emps);
        $this->assertTrue($emps->size() > 0);
    }

    public function testGetDaoInterface() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee8Manager"));
        $emp2 = new ReflectionClass("Employee2Dao");
        $emp2Impl = new ReflectionClass("Employee2DaoImpl");
        $this->assertEquals("Employee2Dao", $dmd->getDaoInterface($emp2)->getName());
        $this->assertEquals("Employee2Dao", $dmd->getDaoInterface($emp2Impl)->getName());
    }

    public function testAutoSelectSqlByDto() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("getEmployeesBySearchConditionList");
        $this->assertNotNull($cmd);
        echo $cmd->getSql();
        $dto = new EmployeeSearchCondition();
        $dto->setDname("RESEARCH");
        $employees =  $cmd->execute(array($dto));
        $this->assertTrue($employees->size() > 0);
    }

    public function testAutoSelectSqlByDto2() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("getEmployeesByEmployeeList");
        $this->assertNotNull($cmd);
        echo $cmd->getSql();
        $dto = $this->getBean("Employee2");
        $this->setProperty($dto, "job", "MANAGER");
        $employees =  $cmd->execute(array($dto));
        var_dump($employees);
    }

    public function testAutoSelectSqlByDto3() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee3Dao"));
        $cmd = $dmd->getSqlCommand("getEmployeesList");
        $this->assertNotNull($cmd);
        echo $cmd->getSql();
        $dto = $this->getBean("Employee3");
        $desc = S2Container_BeanDescFactory::getBeanDesc(new ReflectionClass("Employee3"));
        $propertyDesc = $desc->getPropertyDesc("manager");
        $propertyDesc->setValue($dto, 7902);
        $employees = $cmd->execute(array($dto));
        var_dump($employees);
        $this->assertTrue($employees->size() > 0);
    }

    public function testAutoSelectSqlByDto4() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee3Dao"));
        $cmd = $dmd->getSqlCommand("getEmployees2List");
        $this->assertNotNull($cmd);
        echo $cmd->getSql();
        $this->assertTrue(preg_match('/ ORDER BY empno$/', $cmd->getSql()) == 1);
    }

    public function testAutoSelectSqlByDto5() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("getEmployeesBySearchCondition2List");
        $this->assertNotNull($cmd);
        echo $cmd->getSql();
        $dto = $this->getBean("EmployeeSearchCondition");
        $department = $this->getBean("Department2");
        $this->setProperty($department, "dname", "RESEARCH");
        $this->setProperty($dto, "department", $department);
        $employees =  $cmd->execute((array)$dto);
        $this->assertTrue($employees->size() > 0);
    }

    public function testAutoSelectSqlByDto6() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("getEmployeesBySearchCondition2List");
        $this->assertNotNull($cmd);
        echo $cmd->getSql();
        $dto = $this->getBean("EmployeeSearchCondition");
        $this->setProperty($dto, "department", null);
        $employees =  $cmd->execute(array($dto));
        $this->assertEquals(0, $employees->size());
    }

    public function testSelfReference() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee4Dao"));
        $cmd = $dmd->getSqlCommand("getEmployee");
        $this->assertNotNull($cmd);
        echo $cmd->getSql();
        $employee = $cmd->execute(array(7788));
        var_dump($employee);
        $parent = $this->getProperty($employee, "parent");
        $this->assertEquals(7566, $this->getProperty($parent, "empno"));
    }

//    public function testSelfMultiPk() {
//        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee5Dao"));
//        $cmd = $dmd->getSqlCommand("getEmployee");
//        $this->assertNotNull($cmd);
//        echo $cmd->getSql();
//    }

    public function testNotHavePrimaryKey() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("DepartmentTotalSalaryDao"));
        $cmd = $dmd->getSqlCommand("getTotalSalariesList");
        $this->assertNotNull($cmd);
        echo $cmd->getSql();
        $result =  $cmd->execute(null);
        var_dump($result);
    }

    public function testSelectAutoFullColumnName() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("EmployeeAutoDao"));
        $cmd = $dmd->getSqlCommand("getEmployee");
        echo $cmd->getSql();
    }

    public function testStartsWithOrderBy() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee6Dao"));
        $condition = $this->getBean("EmployeeSearchCondition");
        $this->setProperty($condition, "dname", "RESEARCH");
        $cmd = $dmd->getSqlCommand("getEmployeesArray");
        echo $cmd->getSql();
        $results = $cmd->execute(array($condition));
        $this->setProperty($condition, "orderByString", "ENAME");
        $results = $cmd->execute(array($condition));
    }

    public function testStartsWithBeginComment() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee8Dao"));
        $cmd = $dmd->getSqlCommand("getEmployeesList");
        echo $cmd->getSql();
        {
            $emp = new Employee2();
            $results =  $cmd->execute(array($emp));
            $this->assertEquals(17, $results->size());
        }
        {
            $emp = new Employee2();
            $emp->setEname("SMITH");
            $results =  $cmd->execute(array($emp));
            $this->assertEquals(1, $results->size());
        }
        {
            $emp = new Employee2();
            $emp->setJob("SALESMAN");
            $results =  $cmd->execute(array($emp));
            $this->assertEquals(4, $results->size());
        }
        {
            $emp = new Employee2();
            $emp->setEname("SMITH");
            $emp->setJob("CLERK");
            $results =  $cmd->execute(array($emp));
            $this->assertEquals(1, $results->size());
        }
        {
            $emp = new Employee2();
            $emp->setEname("a");
            $emp->setJob("b");
            $results = $cmd->execute(array($emp));
            $this->assertEquals(0, $results->size());
        }
    }

    public function testQueryAnnotationTx() {
        $dmd = $this->createDaoMetaData($this->getDaoClass("Employee7Dao"));
        $cmd1 = $dmd->getSqlCommand("getCount");
        $cmd2 = $dmd->getSqlCommand("deleteEmployee");
        echo $cmd1->getSql();
        echo $cmd2->getSql();
        $this->assertEquals(17, $cmd1->execute(null));
        $this->assertEquals(1, $cmd2->execute(array(7566)));
        $this->assertEquals(16, $cmd1->execute(null));
    }

}

?>