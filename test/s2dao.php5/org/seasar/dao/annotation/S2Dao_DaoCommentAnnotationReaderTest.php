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
class S2Dao_DaoCommentAnnotationReaderTest extends PHPUnit2_Framework_TestCase {

    private $daoClass = null;
    private $reader = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_DaoCommentAnnotationReaderTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->daoClass = new ReflectionClass("FooDao");
        $daoDesc = S2Container_BeanDescFactory::getBeanDesc($this->daoClass);
        $this->reader = new S2Dao_DaoCommentAnnotationReader($daoDesc);
        S2Container_AnnotationContainer::$DEFAULT_ANNOTATION_READER = 'S2DaoAnnotationReader';
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
        $method1 = $this->daoClass->getMethod("getFoo7");
        $this->assertEquals($this->reader->getReturnType($method1), S2Dao_DaoAnnotationReader::RETURN_MAP);

        $method2 = $this->daoClass->getMethod("getFoo14");
        $this->assertNull($this->reader->getReturnType($method2));
    }

    public function testIsList() {
        $method1 = $this->daoClass->getMethod("getFoo8");
        $this->assertEquals($this->reader->getReturnType($method1), S2Dao_DaoAnnotationReader::RETURN_LIST);

        $method2 = $this->daoClass->getMethod("getFoo9");
        $this->assertNotEquals($this->reader->getReturnType($method2), S2Dao_DaoAnnotationReader::RETURN_LIST);
    }

    public function testIsArray() {
        $method1 = $this->daoClass->getMethod("getFoo8");
        $this->assertNotEquals($this->reader->getReturnType($method1), S2Dao_DaoAnnotationReader::RETURN_ARRAY);

        $method2 = $this->daoClass->getMethod("getFoo9");
        $this->assertEquals($this->reader->getReturnType($method2), S2Dao_DaoAnnotationReader::RETURN_ARRAY);
    }
    
    public function testIsYaml(){
        $method1 = $this->daoClass->getMethod("getFoo12");
        $this->assertEquals($this->reader->getReturnType($method1), S2Dao_DaoAnnotationReader::RETURN_YAML);

        $method2 = $this->daoClass->getMethod("getFoo11");
        $this->assertNotEquals($this->reader->getReturnType($method2), S2Dao_DaoAnnotationReader::RETURN_YAML);
    }

    public function testIsJson(){
        $method1 = $this->daoClass->getMethod("getFoo13");
        $this->assertEquals($this->reader->getReturnType($method1), S2Dao_DaoAnnotationReader::RETURN_JSON);

        $method2 = $this->daoClass->getMethod("getFoo11");
        $this->assertNotEquals($this->reader->getReturnType($method2), S2Dao_DaoAnnotationReader::RETURN_JSON);
    }

    public function testIsObject(){
        $method1 = $this->daoClass->getMethod("getFoo11");
        $this->assertEquals($this->reader->getReturnType($method1), S2Dao_DaoAnnotationReader::RETURN_OBJ);

        $method2 = $this->daoClass->getMethod("getFoo14");
        $this->assertNull($this->reader->getReturnType($method2));
    }
    
    public function testIsMap(){
        $method1 = $this->daoClass->getMethod("getFoo7");
        $this->assertEquals($this->reader->getReturnType($method1), S2Dao_DaoAnnotationReader::RETURN_MAP);

        $method2 = $this->daoClass->getMethod("getFoo11");
        $this->assertNotEquals($this->reader->getReturnType($method2), S2Dao_DaoAnnotationReader::RETURN_MAP);

    }
}
?>
