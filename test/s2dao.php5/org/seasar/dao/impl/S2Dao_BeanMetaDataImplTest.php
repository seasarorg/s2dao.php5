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
class S2Dao_BeanMetaDataImplTest extends PHPUnit2_Framework_TestCase {

    private $connection = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_BeanMetaDataImplTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $ds = $container->getComponent("pdo.dataSource");
        $this->connection = $ds->getConnection();
    }

    protected function tearDown() {
        $this->connection = null;
    }
    
    private function createBeanMetaData(ReflectionClass $class){
        return new S2Dao_BeanMetaDataImpl(
                        $class,
                        $this->connection,
                        new S2Dao_SQLite(),
                        new S2Dao_FieldAnnotationReaderFactory()
                    );
    }
    
    private function getBeanClass($class){
        return new ReflectionClass($class);
    }

    public function testSetup() {
        $bmd = $this->createBeanMetaData($this->getBeanClass("MyBean"));
        $this->assertEquals("MyBean", $bmd->getTableName());
        $this->assertEquals(3, $bmd->getPropertyTypeSize());
        $aaa = $bmd->getPropertyType("aaa");
        $this->assertEquals("aaa", $aaa->getColumnName());
        $bbb = $bmd->getPropertyType("bbb");
        $this->assertEquals("myBbb", $bbb->getColumnName());
        $this->assertEquals(1, $bmd->getRelationPropertyTypeSize());
        $rpt = $bmd->getRelationPropertyType(0);
        $this->assertEquals(1, $rpt->getKeySize());
        $this->assertEquals("ddd", $rpt->getMyKey(0));
        $this->assertEquals("id", $rpt->getYourKey(0));
        $this->assertNotNull($bmd->getIdentifierGenerator());
        $this->assertEquals(1, $bmd->getPrimaryKeySize());
        $this->assertEquals("aaa", $bmd->getPrimaryKey(0));
    }

    public function testSetupDatabaseMetaData() {
        $bmd = $this->createBeanMetaData($this->getBeanClass("Employee2"));
        $empno = $bmd->getPropertyType("empno");
        $this->assertTrue($empno->isPrimaryKey());
        $this->assertTrue($empno->isPersistent());
        $ename = $bmd->getPropertyType("ename");
        $this->assertFalse($ename->isPrimaryKey());
        $dummy = $bmd->getPropertyType("dummy");
        $this->assertFalse($dummy->isPersistent());
    }

    public function testSetupAutoSelectList() {
        $bmd = $this->createBeanMetaData($this->getBeanClass("Department"));
        $bmd2 = $this->createBeanMetaData($this->getBeanClass("Employee2"));
        $sql = $bmd->getAutoSelectList();
        $sql2 = $bmd2->getAutoSelectList();
        var_dump($sql);
        var_dump($sql2);

        $this->assertTrue(strpos("EMP.deptno", $sql2) > 0);
        $this->assertTrue(strpos("department.deptno AS deptno_0", $sql2) > 0);
        $this->assertTrue(strpos("dummy_0", $sql2) < 0);
    }

    public function testConvertFullColumnName() {
        $bmd = $this->createBeanMetaData($this->getBeanClass("Employee2"));
        $this->assertEquals("EMP.empno", $bmd->convertFullColumnName("empno"));
        $this->assertEquals("department.dname", $bmd->convertFullColumnName("dname_0"));
    }

    public function testHasPropertyTypeByAliasName() {
        $bmd = $this->createBeanMetaData($this->getBeanClass("Employee2"));
        $this->assertTrue($bmd->hasPropertyTypeByAliasName("empno"));
        $this->assertTrue($bmd->hasPropertyTypeByAliasName("dname_0"));
        $this->assertFalse($bmd->hasPropertyTypeByAliasName("xxx"));
        $this->assertFalse($bmd->hasPropertyTypeByAliasName("xxx_10"));
        $this->assertFalse($bmd->hasPropertyTypeByAliasName("xxx_0"));
    }

    public function testGetPropertyTypeByAliasName() {
        $bmd = $this->createBeanMetaData($this->getBeanClass("Employee2"));
        $this->assertNotNull($bmd->getPropertyTypeByAliasName("empno"));
        $this->assertNotNull($bmd->getPropertyTypeByAliasName("dname_0"));
    }

    public function testSelfReference() {
        $bmd = $this->createBeanMetaData($this->getBeanClass("Employee4"));
        $rpt = $bmd->getRelationPropertyType("parent");
        $this->assertEquals($this->getBeanClass("Employee4"), $rpt->getBeanMetaData()->getBeanClass());
    }

    public function testNoPersistentPropsEmpty() {
        $bmd = $this->createBeanMetaData($this->getBeanClass("Ddd"));
        $pt = $bmd->getPropertyType("name");
        $this->assertFalse($pt->isPersistent());
    }

    public function testNoPersistentPropsDefined() {
        $bmd = $this->createBeanMetaData($this->getBeanClass("Eee"));
        $pt = $bmd->getPropertyType("name");
        $this->assertFalse($pt->isPersistent());
    }

    public function testPrimaryKeyForIdentifier() {
        $bmd = $this->createBeanMetaData($this->getBeanClass("IdentityTable"));
        $this->assertEquals("id", $bmd->getPrimaryKey(0));
    }

    public function testGetVersionNoPropertyName() {
        $bmd = $this->createBeanMetaData($this->getBeanClass("Fff"));
        $this->assertEquals("version", $bmd->getVersionNoPropertyName());
    }

    public function testGetTimestampPropertyName() {
        $bmd = $this->createBeanMetaData($this->getBeanClass("Fff"));
        $this->assertEquals("updated", $bmd->getTimestampPropertyName());
    }
}
?>
