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
class S2Dao_PostgreSQLProcedureMetaDataImplTest extends PHPUnit2_Framework_TestCase {

    private $pmeta = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PostgreSQLProcedureMetaDataImplTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $dataSource = $container->getComponent("pdo.dataSource");
        $this->pmeta = new S2Dao_PostgreSQLProcedureMetaDataImpl($dataSource->getConnection(),
                                                            new S2Dao_PostgreSQL());
    }

    protected function tearDown() {
        $this->pmeta = null;
    }

    public function testGetProcedures() {
        $infos = $this->pmeta->getProcedures(null, null, "sales_tax");
        var_dump($infos);
        $this->assertNotNull($infos);
        $this->assertEquals(1, count($infos));
    }

    public function testGetProcedureColumnsIn() {
        $infos = $this->pmeta->getProcedures(null, null, "sales_tax");
        $columnIn = $this->pmeta->getProcedureColumnsIn($infos[0]);
        var_dump($columnIn);
        $this->assertNotNull($columnIn);
        $this->assertTrue(is_array($columnIn));
        $type = $columnIn[0];
        $this->assertTrue($type instanceof S2Dao_ProcedureType);
        $this->assertEquals($type->getName(), "sales");
        $this->assertEquals($type->getInout(), S2Dao_ProcedureMetaData::INTYPE);
    }

    public function testGetProcedureColumnsIn2() {
        $infos = $this->pmeta->getProcedures(null, null, "sales_tax2");
        $columnIn = $this->pmeta->getProcedureColumnsIn($infos[0]);
        var_dump($columnIn);
        $this->assertNotNull($columnIn);
        $this->assertTrue(is_array($columnIn));
        $type = $columnIn[0];
        $this->assertTrue($type instanceof S2Dao_ProcedureType);
        $this->assertEquals($type->getName(), "sales");
        $this->assertEquals($type->getInout(), S2Dao_ProcedureMetaData::INTYPE);
    }

    public function testGetProcedureColumnsIn3() {
        $infos = $this->pmeta->getProcedures(null, null, "sales_tax3");
        $columnIn = $this->pmeta->getProcedureColumnsIn($infos[0]);
        var_dump($columnIn);
        $this->assertNotNull($columnIn);
        $this->assertTrue(is_array($columnIn));
        $type = $columnIn[0];
        $this->assertTrue($type instanceof S2Dao_ProcedureType);
        $this->assertEquals($type->getName(), "sales");
        $this->assertEquals($type->getInout(), S2Dao_ProcedureMetaData::INTYPE);
    }
    
    public function testGetProcedureColumnsIn4() {
        $infos = $this->pmeta->getProcedures(null, null, "sales_tax4");
        $columnIn = $this->pmeta->getProcedureColumnsIn($infos[0]);
        var_dump($columnIn);
        $this->assertNotNull($columnIn);
        $this->assertTrue(is_array($columnIn));
        $this->assertEquals($columnIn[0]->getName(), "sales");
        $this->assertEquals($columnIn[0]->getInout(), S2Dao_ProcedureMetaData::INTYPE);
    }

    public function testGetProcedureColumnsOut() {
        $infos = $this->pmeta->getProcedures(null, null, "sales_tax4");
        $columnOut = $this->pmeta->getProcedureColumnsOut($infos[0]);
        var_dump($columnOut);
        $this->assertNotNull($columnOut);
        $this->assertEquals($columnOut[0]->getName(), "tox");
        $this->assertEquals($columnOut[0]->getInout(), S2Dao_ProcedureMetaData::OUTTYPE);
        $this->assertEquals($columnOut[1]->getName(), "total");
        $this->assertEquals($columnOut[1]->getInout(), S2Dao_ProcedureMetaData::OUTTYPE);
    }

    public function testGetProcedureColumnsInOut() {
        $infos = $this->pmeta->getProcedures(null, null, "sales_tax4");
        $columnInOut = $this->pmeta->getProcedureColumnsInOut($infos[0]);
        var_dump($columnInOut);
        $this->assertNotNull($columnInOut);
        $this->assertEquals(0, count($columnInOut));
        $this->assertEquals($columnInOut[0], null);
    }

    public function testGetProcedureColumnReturn() {
        $infos = $this->pmeta->getProcedures(null, null, "sales_tax4");
        $columnReturn = $this->pmeta->getProcedureColumnReturn($infos[0]);
        $this->assertNull($columnReturn);
    }
}
?>
