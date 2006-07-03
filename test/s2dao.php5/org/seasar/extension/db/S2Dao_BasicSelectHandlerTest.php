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
class S2Dao_BasicSelectHandlerTest extends PHPUnit2_Framework_TestCase {

    private $dataSource = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_BasicSelectHandlerTest");
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

    public function testExecute() {
        $sql = "select * from emp2 where empno = ?";
        $handler = new S2Dao_BasicSelectHandler($this->getDataSource(),
                $sql, new S2Dao_ObjectResultSetHandler());
        $ret = $handler->execute(array(7788), (array)gettype(7788));
        var_dump($ret);
        $this->assertNotNull($ret);
    }
    
    public function testExecute2(){
        $sql = "select count(*) from emp2 where empno = ?";
        $handler = new S2Dao_BasicSelectHandler($this->getDataSource(),
                $sql, new S2Dao_ObjectResultSetHandler());
        $ret = $handler->execute(array(7788), (array)gettype(7788));
        var_dump($ret);
        $this->assertEquals($ret, 1);
    }
    
    public function testExecute3(){
        $sql = "select count(*), emp2.* from emp2 where empno = ?";
        $handler = new S2Dao_BasicSelectHandler($this->getDataSource(),
                $sql, new S2Dao_ObjectResultSetHandler());
        $ret = $handler->execute(array(7788), (array)gettype(7788));
        var_dump($ret);
        $this->assertNotNull($ret);
    }
    
    public function testExecute4(){
        $sql = "select null from emp2 where empno = ?";
        $handler = new S2Dao_BasicSelectHandler($this->getDataSource(),
                $sql, new S2Dao_ObjectResultSetHandler());
        $ret = $handler->execute(array(7788), (array)gettype(7788));
        var_dump($ret);
        $this->assertNotNull($ret);
    }
    
    public function testExecute5(){
        $sql = "select null from emp2";
        $handler = new S2Dao_BasicSelectHandler($this->getDataSource(),
                $sql, new S2Dao_ObjectResultSetHandler());
        $ret = $handler->execute(null, null);
        var_dump($ret);
        $this->assertNotNull($ret);
    }
    
    public function testExecute6(){
        $dbms = S2Dao_DbmsManager::getDbms($this->getDataSource()->getConnection());
        // if null columnset
        if(!($dbms instanceof S2Dao_PostgreSQL)){
            return;
        } 
        $sql = "select null from emp2 where empno = ?";
        $handler = new S2Dao_BasicSelectHandler($this->getDataSource(),
                $sql, new S2Dao_ObjectResultSetHandler());
        $ret = $handler->execute(array(7788), (array)gettype(7788));
        var_dump($ret);
        $this->assertNull($ret);
    }

}
?>
