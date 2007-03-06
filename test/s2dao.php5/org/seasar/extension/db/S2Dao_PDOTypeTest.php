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
class S2Dao_PDOTypeTest extends PHPUnit2_Framework_TestCase {

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PDOTypeTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
    }

    protected function tearDown() {
    }

    public function testGettypeNull() {
        $this->assertEquals(S2Dao_PDOType::gettype(null), PDO::PARAM_NULL);
        $this->assertEquals(S2Dao_PDOType::gettype(), PDO::PARAM_NULL);
        $this->assertEquals(S2Dao_PDOType::gettype(gettype(null)), PDO::PARAM_NULL);
    }
    
    public function testGettypeString() {
        $this->assertEquals(S2Dao_PDOType::gettype(gettype("")), PDO::PARAM_STR);
        $this->assertEquals(S2Dao_PDOType::gettype(gettype("null")), PDO::PARAM_STR);
        $this->assertEquals(S2Dao_PDOType::gettype(gettype('')), PDO::PARAM_STR);
        $this->assertEquals(S2Dao_PDOType::gettype(gettype("0")), PDO::PARAM_STR);
        $this->assertEquals(S2Dao_PDOType::gettype(gettype("\0")), PDO::PARAM_STR);
    }
    
    public function testGettypeInteger() {
        $this->assertEquals(S2Dao_PDOType::gettype(gettype(1)), PDO::PARAM_INT);
        $this->assertEquals(S2Dao_PDOType::gettype(gettype(0)), PDO::PARAM_INT);
    }
    
    public function testGettypeBoolean() {
        $this->assertEquals(S2Dao_PDOType::gettype(gettype(true)), PDO::PARAM_BOOL);
        $this->assertEquals(S2Dao_PDOType::gettype(gettype(false)), PDO::PARAM_BOOL);
    }
    
    public function testGettypeOther() {
        $this->assertEquals(S2Dao_PDOType::gettype(new stdClass), PDO::PARAM_STMT);
    }
}
?>
