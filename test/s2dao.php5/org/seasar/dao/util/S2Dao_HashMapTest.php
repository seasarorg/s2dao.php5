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
class S2Dao_HashMapTest extends PHPUnit2_Framework_TestCase {

    private $map = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_HashMapTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $this->map = new S2Dao_HashMap();
        $this->map->put("a", array("aa", "aaa", "aaaa"));
        $this->map->put("b", "b");
        $this->map->put("c", new ArrayObject(array("cc", "ccc")));
        $this->map->put("d", true);
    }

    protected function tearDown() {
        $this->map = null;
    }

    public function testSize() {
        $this->assertEquals(4, $this->map->size());
    }

    public function testIsEmpty() {
        $this->assertFalse($this->map->isEmpty());
    }

    public function testGet() {
        $this->map->put("a", array("aa", "aaa", "aaaa"));
        $this->map->put("b", "b");
        $this->map->put("c", new ArrayObject(array("cc", "ccc")));
        $this->map->put("d", true);

        $this->assertEquals(array("aa", "aaa", "aaaa"), $this->map->get("a"));
        $this->assertEquals("b", $this->map->get("b"));
        $this->assertEquals(new ArrayObject(array("cc", "ccc")), $this->map->get("c"));
        $this->assertTrue($this->map->get("d"));
        $this->assertNull($this->map->get("e"));
    }

    public function testPut() {
        $this->map->put("a", "hoge");
        $this->assertEquals("hoge", $this->map->get("a"));
        $this->assertEquals(4, $this->map->size());
    }

    public function testRemove() {
        $this->map->remove("a");
        $this->map->remove("b");
        $this->assertEquals(2, $this->map->size());
        $this->assertNull($this->map->get("a"));
        $this->assertNull($this->map->get("b"));
    }

    public function testContainsKey() {
        $this->assertTrue($this->map->containsKey("a"));
        $this->assertTrue($this->map->containsKey("c"));
        $this->assertFalse($this->map->containsKey("hoge"));
    }

    public function testToArray() {
        $test = array(
                    "a" => array("aa", "aaa", "aaaa"),
                    "b" => "b",
                    "c" => new ArrayObject(array("cc", "ccc")),
                    "d" => true,
                );
                
        $this->assertEquals($test, $this->map->toArray());
        $this->assertEquals(count($test), count($this->map->toArray()));
    }
}
?>
