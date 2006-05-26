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
class S2Dao_BeanConstantAnnotationReaderTest extends PHPUnit2_Framework_TestCase {

    private $readerFactory = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_BeanConstantAnnotationReaderTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->readerFactory = new S2Dao_FieldAnnotationReaderFactory();
    }

    protected function tearDown() {
        $this->readerFactory = null;
    }

    public function testGetColumnAnnotation() {
        $clazz = new ReflectionClass("AnnotationTestBean1");
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($clazz);
        $reader = $this->readerFactory->createBeanAnnotationReader($clazz);
        
        $pd = $reader->getColumnAnnotation($beanDesc->getPropertyDesc("prop1"));
        $this->assertEquals("Cprop1", $pd);
        $pd = $reader->getColumnAnnotation($beanDesc->getPropertyDesc("prop2"));
        $this->assertEquals("prop2", $pd);
    }

    public function testGetTableAnnotation() {
        $clazz1 = new ReflectionClass("AnnotationTestBean1");
        $reader1 = $this->readerFactory->createBeanAnnotationReader($clazz1);
        $this->assertEquals("TABLE", $reader1->getTableAnnotation());
        
        $clazz2 = new ReflectionClass("AnnotationTestBean2");
        $reader2 = $this->readerFactory->createBeanAnnotationReader($clazz2);
        $this->assertNull($reader2->getTableAnnotation());
    }

    public function testGetVersionNoPropertyNameAnnotation() {
        $clazz1 = new ReflectionClass("AnnotationTestBean1");
        $reader1 = $this->readerFactory->createBeanAnnotationReader($clazz1);
        $str1 = $reader1->getVersionNoPropertyNameAnnotation();
        $this->assertEquals("myVersionNo", $str1);

        $clazz2 = new ReflectionClass("AnnotationTestBean2");
        $reader2 = $this->readerFactory->createBeanAnnotationReader($clazz2);
        $str2 = $reader2->getVersionNoPropertyNameAnnotation();
        $this->assertNull($str2);
    }

    public function testGetTimestampPropertyName() {
        $clazz1 = new ReflectionClass("AnnotationTestBean1");
        $reader1 = $this->readerFactory->createBeanAnnotationReader($clazz1);
        $str1 = $reader1->getTimestampPropertyName();
        $this->assertEquals("myTimestamp", $str1);
        
        $clazz2 = new ReflectionClass("AnnotationTestBean2");
        $reader2 = $this->readerFactory->createBeanAnnotationReader($clazz2);
        $str2 = $reader2->getTimestampPropertyName();
        $this->assertNull($str2);
    }

    public function testGetId() {
        $clazz1 = new ReflectionClass("AnnotationTestBean1");
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($clazz1);
        $reader1 = $this->readerFactory->createBeanAnnotationReader($clazz1);
        
        $str1 = $reader1->getId($beanDesc->getPropertyDesc("prop1"));
        $this->assertEquals("sequence, sequenceName=myseq", $str1);
        
        $str2 = $reader1->getId($beanDesc->getPropertyDesc("prop2"));
        $this->assertNull($str2);
    }

    public function testGetNoPersisteneProps() {
        $clazz1 = new ReflectionClass("AnnotationTestBean1");
        $reader1 = $this->readerFactory->createBeanAnnotationReader($clazz1);
        $strings1 = $reader1->getNoPersisteneProps();
        $this->assertEquals("prop2", $strings1[0]);
        
        $clazz2 = new ReflectionClass("AnnotationTestBean2");
        $reader2 = $this->readerFactory->createBeanAnnotationReader($clazz2);
        $strings2 = $reader2->getNoPersisteneProps();
        $this->assertNull($strings2);
    }

    public function testGetRelationKey() {
        $clazz1 = new ReflectionClass("AnnotationTestBean1");
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($clazz1);
        $reader1 = $this->readerFactory->createBeanAnnotationReader($clazz1);
        $pd = $beanDesc->getPropertyDesc("department");
        $this->assertTrue($reader1->hasRelationNo($pd));
        $this->assertEquals(0, $reader1->getRelationNo($pd));
        $this->assertEquals("DEPTNUM:DEPTNO", $reader1->getRelationKey($pd));
        $this->assertFalse($reader1->hasRelationNo($beanDesc->getPropertyDesc("prop2")));
    }

}
?>
