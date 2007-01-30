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
class S2Dao_DatabaseMetaDataUtilTest extends PHPUnit2_Framework_TestCase {

    private $connection = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_DatabaseMetaDataUtilTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $datasource = $container->getComponent("pdo.dataSource");
        $this->connection = $datasource->getConnection();
    }

    protected function tearDown() {
        $this->connection = null;
    }

    public function testGetPrimaryKeys() {
        $deptPk = array("DEPTNO");
        $pks = S2Dao_DatabaseMetaDataUtil::getPrimaryKeys($this->connection, "DEPT2");
        $this->assertEquals($pks, $deptPk);
    }

    public function testGetPrimaryKeySet() {
        $deptPKList = new S2Dao_ArrayList(array("DEPTNO"));
        $pset = S2Dao_DatabaseMetaDataUtil::getPrimaryKeySet($this->connection, "DEPT2");
        $this->assertEquals($pset, $deptPKList);
    }

    public function testGetColumns() {
        $col = array("DEPTNO", "DNAME", "LOC", "VERSIONNO", "ACTIVE");
        $columns = S2Dao_DatabaseMetaDataUtil::getColumns($this->connection, "DEPT2");
        $this->assertEquals($col, $columns);
    }

    public function testGetColumnSet() {
        $col = new S2Dao_ArrayList(array("DEPTNO", "DNAME", "LOC", "VERSIONNO", "ACTIVE"));
        $columnSet = S2Dao_DatabaseMetaDataUtil::getColumnSet($this->connection, "DEPT2");
        $this->assertEquals($col, $columnSet);
    }

}
?>
