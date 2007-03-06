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
class S2Dao_DataRowImplTest extends PHPUnit2_Framework_TestCase {

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_DataRowImplTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
    }

    protected function tearDown() {
    }

    public function testModify() {
        $table = new S2Dao_DataTableImpl("hoge");
        $table->addColumn("aaa", S2Dao_ColumnTypes::STRING);
        $row = $table->addRow();
        $row->setValue("aaa", "hoge");
        $this->assertEquals(S2Dao_RowStates::CREATED, $row->getState());
        $row->remove();
        $row->setValue("aaa", "hoge");
        $this->assertEquals(S2Dao_RowStates::REMOVED, $row->getState());
        $row->setState(S2Dao_RowStates::UNCHANGED);
        $row->setValue("aaa", "hoge");
        $this->assertEquals(S2Dao_RowStates::MODIFIED, $row->getState());
    }

    public function testSetValue() {
        $table = new S2Dao_DataTableImpl("hoge");
        $table->addColumn("aaa", S2Dao_ColumnTypes::STRING);
        $row = $table->addRow();
        $row->setValue(0, "hoge");
        $this->assertEquals("hoge", $row->getValue(0));
        $this->assertEquals("hoge", $row->getValue("aaa"));
    }

    public function testGetValue() {
        $table = new S2Dao_DataTableImpl("hoge");
        $table->addColumn("aaa_0");
        $table->addColumn("bbbCcc");
        $row = $table->addRow();
        $row->setValue(0, "hoge");
        $row->setValue(1, "hoge2");
        $this->assertEquals("hoge", $row->getValue("aaa_0"));
        $this->assertEquals("hoge2", $row->getValue("bbbCcc"));
    }

    public function testEquals() {
        $table = new S2Dao_DataTableImpl("hoge");
        $table->addColumn("aaa");
        $table->addColumn("bbb");
        $row = $table->addRow();
        $row2 = $table->addRow();
        $this->assertEquals($row, $row);
        $this->assertTrue($row->equals($row2));
        $this->assertFalse($row->equals(null));
        $row->setValue(0, "111");
        $row->setValue(1, "222");
        $row2->setValue(0, "111");
        $row2->setValue(1, "222");
        $this->assertTrue($row == $row2);
        $row->setValue(0, null);
        $this->assertFalse($row->equals($row2));
        $row2->setValue(0, null);
        $this->assertTrue($row == $row2);
    }

    public function testEquals2() {
        $table = new S2Dao_DataTableImpl("hoge");
        $table->addColumn("aaa");
        $table->addColumn("bbb");
        $table2 = new S2Dao_DataTableImpl("hoge2");
        $table2->addColumn("ccc");
        $table2->addColumn("aaa");
        $table2->addColumn("bbb");
        $row = $table->addRow();
        $row2 = $table2->addRow();
        $row->setValue("aaa", "111");
        $row->setValue("bbb", "222");
        $row2->setValue("aaa", "111");
        $row2->setValue("bbb", "222");
        $row2->setValue("ccc", "333");
        $this->assertFalse($row->equals($row2));
    }

    public function testEquals3() {
        $table = new S2Dao_DataTableImpl("hoge");
        $table->addColumn("aaa_bbb");
        $table->addColumn("ccc_0");
        $table->addColumn("dddEee");
        $table2 = new S2Dao_DataTableImpl("hoge2");
        $table2->addColumn("aaaBbb");
        $table2->addColumn("ccc_0");
        $table2->addColumn("ddd_eee");
        $row = $table->addRow();
        $row2 = $table2->addRow();
        $row->setValue("aaa_bbb", "111");
        $row->setValue("ccc_0", "222");
        $row->setValue("dddEee", "333");
        $row2->setValue("aaaBbb", "111");
        $row2->setValue("ccc_0", "222");
        $row2->setValue("ddd_eee", "333");
        $this->assertFalse($row->equals($row2));
    }

    public function testEquals4() {
        $table = new S2Dao_DataTableImpl("hoge");
        $table->addColumn("aaa", S2Dao_ColumnTypes::OBJECT);
        $table2 = new S2Dao_DataTableImpl("hoge2");
        $table2->addColumn("aaa", S2Dao_ColumnTypes::STRING);
        $row = $table->addRow();
        $row2 = $table2->addRow();
        $b1 = array('1');
        $b2 = array('1');
        $row->setValue("aaa", $b1);
        $row2->setValue("aaa", $b2);
        $this->assertFalse($row->equals($row2));
    }

    public function testEquals5() {
        $table = new S2Dao_DataTableImpl("hoge");
        $table->addColumn("aaa");
        $table->addColumn("bbb");
        $table2 = new S2Dao_DataTableImpl("hoge2");
        $table2->addColumn("aaa");
        $table2->addColumn("bbb");
        $row = $table->addRow();
        $row2 = $table2->addRow();
        $row->setValue("aaa", 0);
        $row2->setValue("aaa", 0);
        $this->assertFalse($row->equals($row2));
    }

    public function testEquals6() {
        $table = new S2Dao_DataTableImpl("hoge");
        $table->addColumn("aaa");
        $table2 = new S2Dao_DataTableImpl("hoge2");
        $table2->addColumn("aaa");
        $row = $table->addRow();
        $row2 = $table2->addRow();
        $row->setValue("aaa", "111");
        $row2->setValue("aaa", "111  ");
        $this->assertFalse($row->equals($row2));
    }

    public function testCopyFromBean() {
        $table = new S2Dao_DataTableImpl("hoge");
        $table->addColumn("aaa");
        $table->addColumn("bbb");
        $table->addColumn("test_ccc");
        $table->addColumn("ddd");
        $row = $table->addRow();
        $bean = new MyBean2();
        $bean->setAaa(111);
        $bean->setBbb("222");
        $bean->setTestCcc(333);
        $bean->setDdd(444);
        $row->copyFrom($bean);
        $this->assertEquals(111, $row->getValue("aaa"));
        $this->assertEquals("222", $row->getValue("bbb"));
        $this->assertEquals(444, $row->getValue("ddd"));
    }

    public function testCopyFromRow() {
        $table = new S2Dao_DataTableImpl("hoge");
        $table->addColumn("aaa");
        $table->addColumn("bbb");
        $table->addColumn("test_ccc");
        $row = $table->addRow();
        $row->setValue("aaa", "111");
        $row->setValue("bbb", "222");
        $row->setValue("test_ccc", "333");
        $row2 = $table->addRow();
        $row2->copyFrom($row);
        $this->assertEquals("111", $row2->getValue("aaa"));
        $this->assertEquals("222", $row2->getValue("bbb"));
        $this->assertEquals("333", $row2->getValue("test_ccc"));
    }
}

?>