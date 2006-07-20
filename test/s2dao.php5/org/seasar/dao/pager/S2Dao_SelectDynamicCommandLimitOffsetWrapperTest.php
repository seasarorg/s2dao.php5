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
// | Authors: yonekawa                                                       |
// +----------------------------------------------------------------------+
// $Id$
//
/**
 * @author yonekawa
 */
class S2Dao_SelectDynamicCommandLimitOffsetWrapperTest extends PHPUnit2_Framework_TestCase {

    private $dataSource;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_SelectDynamicCommandLimitOffsetWrapperTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dataSource = $container->getComponent("pdo.dataSource");
    }

    protected function tearDown() {
        $this->dataSource = null;
    }
    
    private function createBeanMetaData($class){
        $conn = $this->dataSource->getConnection();
        return new S2Dao_BeanMetaDataImpl(
                        new ReflectionClass($class),
                        $conn,
                        S2Dao_DbmsManager::getDbms($conn));
    }

    /**
     * @todo yonekawa
     */
    public function testExecute() {
        $cmd = new S2Dao_SelectDynamicCommand($this->dataSource,
                new S2Dao_BasicStatementFactory(),
                new S2Dao_BeanMetaDataResultSetHandler(
                        $this->createBeanMetaData("Employee2")),
                new S2Dao_BasicResultSetFactory());
        $cmd->setSql("SELECT * FROM emp2");

        $empAll = $cmd->execute(array());
        
        $args = array();
        $condition = new S2Dao_DefaultPagerCondition();
        $condition->setLimit(5);
        $condition->setOffset(2);
        $args[] = $condition;

        $cmdWrapper = new S2Dao_SelectDynamicCommandLimitOffsetWrapper($cmd);
        $emp = $cmdWrapper->execute($args);
        
        var_dump($emp);
        var_dump($empAll);

        $this->assertEquals(count($empAll), $condition->getCount());
        $this->assertEquals(count($emp), $condition->getLimit());

// FIXME: please fix testcase this; yonekawa
//        $wrapper = new S2Dao_SelectDynamicCommandLimitOffsetWrapper($cmd);
//        $emp = $wrapper->execute();
//        var_dump($emp);
//        $this->assertNotNull($emp);
    }
}

?>