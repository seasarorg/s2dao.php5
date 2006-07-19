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
class S2Dao_PropertyTypeImplTest extends PHPUnit2_Framework_TestCase {

    private $pt1 = null;
    private $pt2 = null;
    private $pt3 = null;
    private $pt4 = null;
    private $pt5 = null;
    private $pt6 = null;
    private $pt7 = null;
    private $pt8 = null;
    private $pt9 = null;
    private $pt10 = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PropertyTypeImplTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->pt1 = $this->getPt1();
        $this->pt1->setPrimaryKey(true);
        $this->pt1->setPersistent(true);
        $this->pt2 = $this->getPt2();
        $this->pt2->setPrimaryKey(false);
        $this->pt2->setPersistent(true);
        $this->pt3 = $this->getPt3();
        $this->pt3->setPrimaryKey(true);
        $this->pt3->setPersistent(false);
        $this->pt4 = $this->getPt4();
        $this->pt4->setPrimaryKey(false);
        $this->pt4->setPersistent(false);
        $this->pt5 = $this->getPt5();
        $this->pt6 = $this->getPt6();
        $this->pt7 = $this->getPt7();
        $this->pt8 = $this->getPt8();
        $this->pt9 = $this->getPt9();
        $this->pt10 = $this->getPt10();
    }

    protected function tearDown() {
        $this->pt1 = null;
        $this->pt2 = null;
        $this->pt3 = null;
        $this->pt4 = null;
        $this->pt5 = null;
        $this->pt6 = null;
        $this->pt7 = null;
        $this->pt8 = null;
        $this->pt9 = null;
        $this->pt10 = null;
    }
    
    private function getPropertyDesc($className, $propertyName){
        $bd = S2Container_BeanDescFactory::getBeanDesc(new ReflectionClass($className));
        return $bd->getPropertyDesc($propertyName);
    }
    
    private function getPt1(){
        $pd = $this->getPropertyDesc("ptBean", "aaa");
        return new S2Dao_PropertyTypeImpl($pd, null, null);
    }
    
    private function getPt2(){
        $pd = $this->getPropertyDesc("ptBean", "aaa");
        return new S2Dao_PropertyTypeImpl($pd, null, "bbb");
    }

    private function getPt3(){
        $pd = $this->getPropertyDesc("ptBean", "hoge");
        return new S2Dao_PropertyTypeImpl($pd, null, "aaa");
    }
    
    private function getPt4(){
        $pd = $this->getPropertyDesc("ptBean", "hoge");
        return new S2Dao_PropertyTypeImpl($pd, null, null);
    }

    private function getPt5(){
        $pd = $this->getPropertyDesc("ptBean", "baz");
        return new S2Dao_PropertyTypeImpl($pd, null, "bar");
    }
    
    private function getPt6(){
        $pd = $this->getPropertyDesc("ptBean", "baz");
        return new S2Dao_PropertyTypeImpl($pd, null, null);
    }
    
    private function getPt7(){
        return new S2Dao_PropertyTypeImpl("hoge","foo","bar");
    }
    
    private function getPt8(){
        return new S2Dao_PropertyTypeImpl("aaa", null, null);
    }

    private function getPt9(){
        return new S2Dao_PropertyTypeImpl(1, null, "bbb");
    }

    private function getPt10(){
        return new S2Dao_PropertyTypeImpl("", null, null);
    }

    public function testGetPropertyDesc() {
        $this->assertTrue($this->pt1->getPropertyDesc() instanceof S2Container_PropertyDesc);
        $this->assertTrue($this->pt2->getPropertyDesc() instanceof S2Container_PropertyDesc);
        $this->assertTrue($this->pt3->getPropertyDesc() instanceof S2Container_PropertyDesc);
        $this->assertTrue($this->pt4->getPropertyDesc() instanceof S2Container_PropertyDesc);
        $this->assertTrue($this->pt5->getPropertyDesc() instanceof S2Container_PropertyDesc);
        $this->assertTrue($this->pt6->getPropertyDesc() instanceof S2Container_PropertyDesc);
        $this->assertFalse($this->pt7->getPropertyDesc() instanceof S2Container_PropertyDesc);
        $this->assertNull($this->pt7->getPropertyDesc());
        $this->assertFalse($this->pt8->getPropertyDesc() instanceof S2Container_PropertyDesc);
        $this->assertNull($this->pt8->getPropertyDesc());
        $this->assertFalse($this->pt9->getPropertyDesc() instanceof S2Container_PropertyDesc);
        $this->assertNull($this->pt9->getPropertyDesc());
        $this->assertFalse($this->pt10->getPropertyDesc() instanceof S2Container_PropertyDesc);
        $this->assertNull($this->pt10->getPropertyDesc());
    }

    public function testGetPropertyName() {
        $this->assertEquals($this->pt1->getPropertyName(), "aaa");
        $this->assertEquals($this->pt2->getPropertyName(), "aaa");
        $this->assertEquals($this->pt3->getPropertyName(), "hoge");
        $this->assertEquals($this->pt4->getPropertyName(), "hoge");
        $this->assertEquals($this->pt5->getPropertyName(), "baz");
        $this->assertEquals($this->pt6->getPropertyName(), "baz");
        $this->assertEquals($this->pt7->getPropertyName(), "hoge");
        $this->assertEquals($this->pt8->getPropertyName(), "aaa");
        $this->assertEquals($this->pt9->getPropertyName(), null);
        $this->assertEquals($this->pt10->getPropertyName(), "");
    }

    public function testGetColumnName() {
        $this->assertEquals($this->pt1->getColumnName(), "aaa");
        $this->assertEquals($this->pt2->getColumnName(), "bbb");
        $this->assertEquals($this->pt3->getColumnName(), "aaa");
        $this->assertEquals($this->pt4->getColumnName(), "hoge");
        $this->assertEquals($this->pt5->getColumnName(), "bar");
        $this->assertEquals($this->pt6->getColumnName(), "baz");
        $this->assertEquals($this->pt7->getColumnName(), "bar");
        $this->assertEquals($this->pt8->getColumnName(), "aaa");
        $this->assertEquals($this->pt9->getColumnName(), "bbb");
        $this->assertEquals($this->pt10->getColumnName(), "");
    }

    public function testGetValueType() {
        $this->assertEquals($this->pt1->getValueType(), gettype(null));
        $this->assertEquals($this->pt2->getValueType(), gettype(null));
        $this->assertEquals($this->pt3->getValueType(), gettype(new stdClass));
        $this->assertEquals($this->pt4->getValueType(), gettype(new stdClass));
        $this->assertEquals($this->pt5->getValueType(), gettype(null));
        $this->assertEquals($this->pt6->getValueType(), gettype(null));
        $this->assertEquals($this->pt7->getValueType(), "foo");
        $this->assertEquals($this->pt8->getValueType(), null);
        $this->assertEquals($this->pt9->getValueType(), null);
        $this->assertEquals($this->pt10->getValueType(), null);
    }

    public function testIsPrimaryKey() {
        $this->assertTrue($this->pt1->isPrimaryKey());
        $this->assertFalse($this->pt2->isPrimaryKey());
        $this->assertTrue($this->pt3->isPrimaryKey());
        $this->assertFalse($this->pt4->isPrimaryKey());
        $this->assertFalse($this->pt5->isPrimaryKey());
        $this->assertFalse($this->pt6->isPrimaryKey());
        $this->assertFalse($this->pt7->isPrimaryKey());
        $this->assertFalse($this->pt8->isPrimaryKey());
        $this->assertFalse($this->pt9->isPrimaryKey());
        $this->assertFalse($this->pt10->isPrimaryKey());
    }

    public function testIsPersistent() {
        $this->assertTrue($this->pt1->isPersistent());
        $this->assertTrue($this->pt2->isPersistent());
        $this->assertFalse($this->pt3->isPersistent());
        $this->assertFalse($this->pt4->isPersistent());
        $this->assertTrue($this->pt5->isPersistent());
        $this->assertTrue($this->pt6->isPersistent());
        $this->assertTrue($this->pt7->isPersistent());
        $this->assertTrue($this->pt8->isPersistent());
        $this->assertTrue($this->pt9->isPersistent());
        $this->assertTrue($this->pt10->isPersistent());
    }
}
?>
