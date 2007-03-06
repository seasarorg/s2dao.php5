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
class S2Dao_DefaultImplTest  extends PHPUnit2_Framework_TestCase {
    
    private $defaultTableDao = null;
    private $pkOnlyTableDao = null;
    private $connection = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_DefaultImplTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->connection = $container->getComponent("pdo.dataSource")->getConnection();
        $this->defaultTableDao = $container->getComponent("df.DefaultTableDao");
        $this->pkOnlyTableDao = $container->getComponent("df.PkOnlyTableDao");
    }

    protected function tearDown() {
        $this->defaultTableDao = null;
        $this->pkOnlyTableDao = null;
        $this->connection = null;
    }
    
    private function getConnection(){
        return $this->connection;
    }
    
    public function testInsertByAutoSqlTx() {
        $id = 0;
        {
            $bean = new DefaultTable();
            $bean->setAaa("1234567");
            $bean->setBbb("890");
            $this->defaultTableDao->insert($bean);
            $id = $bean->getId();
        }
        {
            $bean = $this->defaultTableDao->getDefaultTable($id);
            $this->assertEquals("1234567", $bean->getAaa(), "inserted setted value");
            $this->assertEquals("890", $bean->getBbb());
            $this->assertEquals(0, (int)$bean->getVersionNo());
        }
    }

    public function testInsertDefaultByAutoSqlTx() {
        $id = 0;
        {
            $bean = new DefaultTable();
            $bean->setAaa("ABC");
            $bean->setBbb("bbbb");
            $this->defaultTableDao->insert($bean);
            var_dump($bean->getId());
            $id = $bean->getId();
        }
        {
            $bean = $this->defaultTableDao->getDefaultTable($id);
            $this->assertEquals("ABC", $bean->getAaa());
            $this->assertEquals("bbbb", $bean->getBbb());
            $this->assertEquals(0, (int)$bean->getVersionNo());
        }
    }

    public function testThrownExceptionWhenNullDataOnlyTx() {
        $bean = new DefaultTable();
        try {
            $this->defaultTableDao->insert($bean);
            $this->fail("should be thrown SRuntimeException, when only null properties");
        } catch (Exception $e) {
            var_dump($e->getTraceAsString());
            $this->assertNotNull($e);
        }
    }

    public function testInsertByManualSqlTx() {
        $id = 0;
        {
            $bean = new DefaultTable();
            $bean->setAaa("foooo");
            $this->defaultTableDao->insert($bean);
            var_dump($bean);
            $id = $bean->getId();
        }
        {
            $bean = $this->defaultTableDao->getDefaultTable($id);
            $this->assertEquals("foooo", $bean->getAaa());
            $this->assertEquals(null, $bean->getBbb());
            $this->assertEquals(0, (int)$bean->getVersionNo());
        }
    }

    public function testInsertDefaultByManualSqlTx() {
        $id = 0;
        {
            $bean = new DefaultTable();
            $bean->setAaa("ABC");
            $bean->setBbb("ttt");
            $this->defaultTableDao->insertBySql($bean);
            $id = $bean->getId();
        }
        {
            $bean = $this->defaultTableDao->getDefaultTable($id);
            $this->assertEquals("ABC", $bean->getAaa());
            $this->assertEquals("ttt", $bean->getBbb());
            $this->assertEquals(0, (int)$bean->getVersionNo());
        }
    }

    public function testInsertPkOnlyTableTx() {
        $bean = new PkOnlyTable();
        $bean->setAaa(123);
        $bean->setBbb(456);
        $this->pkOnlyTableDao->insert($bean);
        $list = $this->pkOnlyTableDao->findAllList();
        var_dump($list);
        $this->assertEquals(2, $list->size());
    }
}

?>