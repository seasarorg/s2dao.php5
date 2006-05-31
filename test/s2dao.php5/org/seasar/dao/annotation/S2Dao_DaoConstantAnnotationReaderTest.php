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
class S2Dao_DaoConstantAnnotationReaderTest extends PHPUnit2_Framework_TestCase {

    private $daoClass = null;
    private $reader = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_DaoConstantAnnotationReaderTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->daoClass = new ReflectionClass("BarDao");
        $daoDesc = S2Container_BeanDescFactory::getBeanDesc($this->daoClass);
        $this->reader = new S2Dao_DaoConstantAnnotationReader($daoDesc);
    }

    protected function tearDown() {
        $this->daoClass = null;
        $this->reader = null;
    }

    public function testGetBeanClass() {
        $this->assertEquals($this->reader->getBeanClass(), new ReflectionClass("FooBean"));
    }

    public function testGetArgNames() {
        $args = array("id", "num", "desc");
        $method1 = $this->daoClass->getMethod("getFoo");
        $this->assertEquals($args, $this->reader->getArgNames($method1));

        $method2 = $this->daoClass->getMethod("getFoo2");
        $this->assertEquals($args, $this->reader->getArgNames($method2));
    }

    public function testGetNoPersistentProps() {
        $props = array("sal", "comm");
        $method = $this->daoClass->getMethod("getFoo3");
        $this->assertEquals($props, $this->reader->getNoPersistentProps($method));
    }

    public function testGetPersistentProps() {
        $props = array("sal", "comm");
        $method = $this->daoClass->getMethod("getFoo4");
        $this->assertEquals($props, $this->reader->getPersistentProps($method));
    }

    public function testGetQuery() {
        $query = "WHERE id = ? and sal = ?";
        $method1 = $this->daoClass->getMethod("getFoo5");
        $this->assertEquals($query, $this->reader->getQuery($method1));
        
        $method2 = $this->daoClass->getMethod("getFoo2");
        $this->assertNull($this->reader->getQuery($method2));
    }

    public function testGetSQL() {
        $sql = "SELECT * FROM EMP2";
        $method = $this->daoClass->getMethod("getFoo6");
        $this->assertEquals($sql, $this->reader->getSql($method, 'sqlite'));
    }

    public function testGetStoredProcedureName() {
        $procedure = "SALES2";
        $method = $this->daoClass->getMethod("getFoo10");
        $this->assertEquals($procedure, $this->reader->getStoredProcedureName($method));
    }

    public function testGetReturnType() {
        $method1 = $this->daoClass->getMethod("getFoo7Map");
        $this->assertEquals("Map", $this->reader->getReturnType($method1));

        $method2 = $this->daoClass->getMethod("getFoo8List");
        $this->assertNull($this->reader->getReturnType($method2));
    }

    public function testIsSelectList() {
        $method1 = $this->daoClass->getMethod("getFoo8List");
        $this->assertTrue($this->reader->isSelectList($method1) == 1);

        $method2 = $this->daoClass->getMethod("getFoo9Array");
        $this->assertFalse($this->reader->isSelectList($method2) == 1);
    }

    public function testIsSelectArray() {
        $method1 = $this->daoClass->getMethod("getFoo8List");
        $this->assertFalse($this->reader->isSelectArray($method1) == 1);

        $method2 = $this->daoClass->getMethod("getFoo9Array");
        $this->assertTrue($this->reader->isSelectArray($method2) == 1);
    }
}
?>
