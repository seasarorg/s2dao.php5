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
class S2Dao_BasicHandlerTest extends PHPUnit2_Framework_TestCase {

    private $dataSource = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_BasicHandlerTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dataSource = $container->getComponent("pdo.dataSource");
    }

    protected function tearDown() {
        $this->dataSource = null;
    }
    
    private function getDataSource(){
        return $this->dataSource;
    }

    public function testGetDataSource() {
        $sql = "select * from emp2, dept2";
        $handler = new S2Dao_BasicHandler($this->getDataSource(),
                        $sql, new S2Dao_BasicStatementFactory());
                        
        $this->assertNotNull($handler);
        $this->assertEquals($this->getDataSource(), $handler->getDataSource());
    }
    
    public function testGetSql() {
        $sql = "select * from emp2, dept2";
        $handler = new S2Dao_BasicHandler($this->getDataSource(),
                        $sql, new S2Dao_BasicStatementFactory());
                        
        $this->assertNotNull($handler);
        $this->assertEquals($sql, $handler->getSql());
        
        $sql2 = "select * from emp2";
        $handler->setSql($sql2);
        $this->assertNotEquals($sql, $handler->getSql());
    }
    
    public function testGetStatementFactory() {
        $sql = "select * from emp2, dept2";
        $handler1 = new S2Dao_BasicHandler($this->getDataSource(),
                        $sql, new S2Dao_BasicStatementFactory());
        $handler2 = new S2Dao_BasicHandler($this->getDataSource(),
                        $sql, null);
                        
        $this->assertEquals($handler1, $handler2);
    }
}
?>
