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
class S2DaoTestCaseTest extends PHPUnit2_Framework_TestCase {

    private $test = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2DaoTestCaseTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->test = new S2DaoTestCase();
    }

    protected function tearDown() {
        $this->test = null;
    }
    
    public function testCorrectInstance(){
        $this->assertTrue($this->test instanceof S2DaoTestCase);
    }
    
   public function testAssertBeanEquals() {
        $expected = new S2Dao_DataSetImpl();
        $table = $expected->addTable("emp");
        $table->addColumn("aaa");
        $table->addColumn("bbb_0");
        $row = $table->addRow();
        $row->setValue("aaa", "111");
        $row->setValue("bbb_0", "222");
        $bean = new Hoge();
        $bean->setAaa("111");
        $foo = new Foo();
        $foo->setBbb("222");
        $bean->setFoo($foo);
        var_dump($expected);
        var_dump($bean);
    }

    public function testAssertBeanListEquals() {
        $expected = new S2Dao_DataSetImpl();
        $table = $expected->addTable("emp");
        $table->addColumn("aaa");
        $table->addColumn("bbb_0");
        $row = $table->addRow();
        $row->setValue("aaa", "111");
        $row->setValue("bbb_0", "222");
        $bean = new Hoge();
        $bean->setAaa("111");
        $foo = new Foo();
        $foo->setBbb("222");
        $bean->setFoo($foo);
        $list = new S2Dao_ArrayList();
        $list->add($bean);
        var_dump($expected);
        var_dump($list);
    }
}
?>
