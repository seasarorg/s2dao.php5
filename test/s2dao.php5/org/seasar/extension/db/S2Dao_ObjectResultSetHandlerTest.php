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
class S2Dao_ObjectResultSetHandlerTest extends PHPUnit2_Framework_TestCase {

    private $dataSource = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_ObjectResultSetHandlerTest");
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

    public function testHandle() {
        $handler = new S2Dao_ObjectResultSetHandler();
        $sql = "select ename from emp2 where empno = 7788";
        $conn = $this->getDataSource()->getConnection();
        $stmt = $conn->prepare($sql);
        $ret = null;
        $stmt->execute();
        $ret = $handler->handle($stmt);
        
        $this->assertTrue(is_array($ret));
        $this->assertEquals(get_class($ret[0]), "stdClass");
    }
    
    public function testHandle2() {
        $handler = new S2Dao_ObjectResultSetHandler();
        $sql = "select ename from emp2 where empno = 7788";
        $conn = $this->getDataSource()->getConnection();
        $stmt = $conn->prepare($sql);
        $ret = null;
        $stmt->execute();
        $ret = $handler->handle($stmt);
        
        $stdClass = new stdClass();
        $stdClass->ENAME = "SCOTT";
        var_dump($ret);
        $this->assertEquals($ret[0], $stdClass);
    }
    
    public function testHandle3() {
        $handler = new S2Dao_ObjectResultSetHandler(new Aaa());
        $sql = "select ename from emp2 where empno = 7788";
        $conn = $this->getDataSource()->getConnection();
        $stmt = $conn->prepare($sql);
        $ret = null;
        $stmt->execute();
        $ret = $handler->handle($stmt);
        
        $array = array();
        $aaa = new Aaa();
        $aaa->ENAME = "SCOTT";
        $array[0] = $aaa;
        
        $this->assertEquals($ret, $array);
        $this->assertEquals($ret[0], $aaa);
    }
}
?>
