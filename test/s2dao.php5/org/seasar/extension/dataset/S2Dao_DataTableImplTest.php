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
// $Id$
//
/**
 * @author nowel
 */
class S2Dao_DataTableImplTest extends PHPUnit2_Framework_TestCase {
    
    private $dataSource = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_DataTableImplTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dataSource = $container->getComponent("pdo.dataSource");
    }

    protected function tearDown() {
        $this->dataSource = null;
    }

    public function testHandle() {
        $sql = "select * from EMP2 emp2";
        $handler = new S2Dao_BasicSelectHandler($this->dataSource, $sql,
                new S2Dao_DataTableResultSetHandler("EMP2", $this->dataSource));
        $ret = $handler->execute(null, null);
        var_dump($ret);
        $this->assertNotNull($ret);
        $this->assertTrue($ret->getColumn("EMPNO")->isPrimaryKey());
    }

    public function testHandle2() {
        $sql = "select emp2.ename, dept2.dname from EMP2 emp2, DEPT2 dept2 where emp2.deptno = dept2.deptno";
        $handler = new S2Dao_BasicSelectHandler($this->dataSource, $sql,
                new S2Dao_DataTableResultSetHandler("EMP2", $this->dataSource));
        $ret = $handler->execute(null, null);
        var_dump($ret);
        $this->assertNotNull($ret);
        $this->assertTrue($ret->getColumn("ENAME")->isWritable());
        $this->assertFalse($ret->getColumn("DNAME")->isWritable());
    }

}

?>