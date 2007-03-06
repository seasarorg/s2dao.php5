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
class S2Dao_BeanCommentAnnotationReaderTest extends PHPUnit2_Framework_TestCase {

    private $beanDesc = null;
    private $beanAnnotationReader = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_BeanCommentAnnotationReaderTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $beanClass = new ReflectionClass("FooBean");
        $this->beanDesc = S2Container_BeanDescFactory::getBeanDesc($beanClass);
        $this->beanAnnotationReader = new S2Dao_BeanCommentAnnotationReader($this->beanDesc);
        S2Container_AnnotationContainer::$DEFAULT_ANNOTATION_READER = 'S2DaoAnnotationReader';
    }

    protected function tearDown() {
        $this->beanDesc = null;
        $this->beanAnnotationReader = null;
    }
    
    public function testGetColumnAnnotation() {
        $pd = $this->beanDesc->getPropertyDesc('aa');
        $column = $this->beanAnnotationReader->getColumnAnnotation($pd);
        $this->assertEquals("aa", $column);
        $pd = $this->beanDesc->getPropertyDesc('bb');
        $column = $this->beanAnnotationReader->getColumnAnnotation($pd);
        $this->assertNotNull($column);
        $this->assertEquals("BB", $column);
    }

    public function testGetId() {
        $pd = $this->beanDesc->getPropertyDesc('aa');
        $id = $this->beanAnnotationReader->getId($pd);
        $this->assertEquals("assigned", $id);
        $pd = $this->beanDesc->getPropertyDesc('bb');
        $id = $this->beanAnnotationReader->getId($pd);
        $this->assertNull($id);
    }

    public function testGetNoPersisteneProps() {
        $noProp = $this->beanAnnotationReader->getNoPersisteneProps();
        $this->assertEquals($noProp, array("aa", "bb"));
    }

    public function testGetVersionNoPropertyNameAnnotation() {
    }

    public function testGetTimestampPropertyName() {
    }

    public function testGetRelationNo() {
        $pd = $this->beanDesc->getPropertyDesc('bb');
        $relno = $this->beanAnnotationReader->getRelationNo($pd);
        $this->assertNull($relno);
        $pd = $this->beanDesc->getPropertyDesc('cc');
        $relno = $this->beanAnnotationReader->getRelationNo($pd);
        $this->assertNotNull($relno);
        $this->assertEquals(0, $relno);
    }

    public function testGetRelationKey() {
        $pd = $this->beanDesc->getPropertyDesc('cc');
        $relkey = $this->beanAnnotationReader->getRelationKey($pd);
        $this->assertEquals('', $relkey);
        $pd = $this->beanDesc->getPropertyDesc('dd');
        $relkey = $this->beanAnnotationReader->getRelationKey($pd);
        $this->assertNotNull($relkey);
        $this->assertEquals('EMP:EMPNO', $relkey);
    }

    public function testHasRelationNo() {
        $pd = $this->beanDesc->getPropertyDesc('aa');
        $this->assertFalse($this->beanAnnotationReader->hasRelationNo($pd));
        $pd = $this->beanDesc->getPropertyDesc('bb');
        $this->assertFalse($this->beanAnnotationReader->hasRelationNo($pd));
        $pd = $this->beanDesc->getPropertyDesc('cc');
        $this->assertTrue($this->beanAnnotationReader->hasRelationNo($pd));
        $pd = $this->beanDesc->getPropertyDesc('dd');
        $this->assertTrue($this->beanAnnotationReader->hasRelationNo($pd));
    }
}

?>