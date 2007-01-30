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
class S2Dao_IdentifierGeneratorFactoryTest extends PHPUnit2_Framework_TestCase {

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_IdentifierGeneratorFactoryTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
    }

    protected function tearDown() {
    }

    public function testCreateIdentifierGenerator() {
        $dbms = new S2Dao_SQLite();
        $hoge = new Hoge2Bean();
        $hoge->setId(1);
        $generator = S2Dao_IdentifierGeneratorFactory::createIdentifierGenerator("id", $dbms, null);
        $this->assertTrue($generator instanceof S2Dao_AssignedIdentifierGenerator);
        
        $generator = S2Dao_IdentifierGeneratorFactory::createIdentifierGenerator("id", $dbms, "identity");
        $this->assertTrue($generator instanceof S2Dao_IdentityIdentifierGenerator);
        
        $generator = S2Dao_IdentifierGeneratorFactory::createIdentifierGenerator("id", $dbms, "sequence, sequenceName = myseq");
        $this->assertEquals("myseq", $generator->getSequenceName());
    }
}
?>
