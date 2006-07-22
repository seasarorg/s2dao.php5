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
class S2Dao_BeanArrayMetaDataResultSetHandlerTest extends PHPUnit2_Framework_TestCase {

    private $dataSource = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_BeanArrayMetaDataResultSetHandlerTest");
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
        $emp2 = new ReflectionClass("Employee2");
        $handler = new S2Dao_BeanArrayMetaDataResultSetHandler($this->createBeanMetaData($emp2));
        $sql = "select * from EMP2 emp2";
        $conn = $this->getConnection();
        $ps = $conn->prepare($sql);
        $ps->execute();
        $ret = $handler->handle($ps);
        $this->assertNotNull($ret);
        var_dump($ret);
        foreach($ret as $row){
            $emp = current($row);
            var_dump($emp["empno"] . "," . $emp["ename"]);
        }
    }
    
    private function getConnection(){
        return $this->dataSource->getConnection();
    }
    
    private function createBeanMetaData(ReflectionClass $class){
        $conn = $this->getConnection();
        $dbms = new S2Dao_SQLite();
        return new S2Dao_BeanMetaDataImpl($class, $conn, $dbms);
    }
}
?>
