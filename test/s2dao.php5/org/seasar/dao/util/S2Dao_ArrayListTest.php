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
// $Id: $
//
/**
 * @author nowel
 */
class S2Dao_ArrayListTest extends PHPUnit2_Framework_TestCase {

    private $list = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_ArrayListTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->list = new S2Dao_ArrayList(
                array(
                    "a", "b", "c", "d", "e",
                    "hoge" => "hoge",
                    "foo" => "bar",
                )
        );
    }

    protected function tearDown() {
        $this->list = null;
    }
    
    public function testS2Dao_ArrayList(){
        $this->assertTrue($this->list instanceof S2Dao_ArrayList);
    }

    public function testSize() {
        $this->assertEquals(7, $this->list->size());
    }

    public function testIsEmpty() {
        $this->assertFalse($this->list->isEmpty());
    }

    public function testContains() {
        $this->assertTrue($this->list->contains("a"));
        $this->assertTrue($this->list->contains("hoge"));
        $this->assertTrue($this->list->contains("bar"));
        $this->assertFalse($this->list->contains(array("hoge" => "hoge")));
        $this->assertFalse($this->list->contains(array("foo" => "bar")));
    }

    public function testGet() {
        $this->assertEquals("a", $this->list->get(0));
        $this->assertEquals("e", $this->list->get(4));
        $this->assertSame("hoge", $this->list->get("hoge"));
    }

    public function testSet() {
        $this->list->set(5, "hello");
        $this->assertEquals("hello", $this->list->get(5));
        $this->assertEquals(8, $this->list->size());
        $this->assertTrue($this->list->contains("hello"));
    }

    public function testAdd() {
        $this->list->add("hello");
        $this->list->add(6, "world");
        $this->assertEquals("hello", $this->list->get(5));
        $this->assertEquals("world", $this->list->get(6));
        $this->assertEquals(9, $this->list->size());
        $this->assertTrue($this->list->contains("hello"));
    }

    public function testAddAll() {
        $aryObj = new ArrayObject(array("a", "b", "c", "d", "e"));
        $this->list->addAll($aryObj);
        $this->assertEquals(12, $this->list->size());
        $this->assertEquals($this->list->get(0), $this->list->get(5));
        $this->assertEquals($this->list->get(4), $this->list->get(9));
    }

    public function testRemove() {
        $this->list->remove(0);
        $this->list->remove("hoge");
        $this->assertEquals(5, $this->list->size());
        $this->assertFalse($this->list->contains("hoge"));
        $this->assertEquals("b", $this->list->get(1));
    }

    public function testIterator() {
        $itor = $this->list->iterator();
        $this->assertTrue($itor instanceof Iterator);
        $this->assertEquals("a", $itor->current());
        $itor->next();
        $this->assertTrue($itor->valid());
        $this->assertEquals("b", $itor->current());
    }

    public function testToArray() {
        $array = $this->list->toArray();
        $this->assertEquals($array, array("a", "b", "c", "d", "e",
                                            "hoge" => "hoge", "foo" => "bar"));
                                            
        $this->assertEquals(count($array), $this->list->size());
    }
}
?>
