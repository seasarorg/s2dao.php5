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
class S2Dao_AbstractTxInterceptorTest extends PHPUnit2_Framework_TestCase {

    private $exBean_ = null;
    private $tm_ = null;
    private $testTx_ = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_AbstractTxInterceptorTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->tm_ = $container->getComponent("pdo.dataSource");
        $this->testTx_ = $container->getComponent("testTx");
        $this->exBean_ = $container->getComponent("ExceptionBeanImpl");
    }

    protected function tearDown() {
        $this->testTx_ = null;
        $this->tm_ = null;
        $this->exBean_ = null;
    }

    public function testType() {
        try {
            $this->testTx_->addCommitRule(new Exception());
            $this->testTx_->addCommitRule(new S2Container_S2RuntimeException(array()));
            $this->testTx_->addCommitRule(new stdClass());
            $this->fail("1");
        } catch (Exception $expected) {
            var_dump($expected->getTraceAsString());
        }

        try {
            $this->testTx_->addRollbackRule(new Exception());
            $this->testTx_->addRollbackRule(new S2Container_S2RuntimeException());
            $this->testTx_->addRollbackRule("");
            $this->fail("2");
        } catch (Exception $expected) {
            var_dump($expected->getTraceAsString());
        }
    }

    public function testNoRule() {
        try {
            $this->exBean_->invoke();
            $this->fail("1");
        } catch (Exception $expected) {
            var_dump($expected->getTraceAsString());
        }
        $this->assertNull($this->testTx_->result);
    }

    public function testCommitRule() {
        $this->testTx_->addRollbackRule(new S2Container_S2RuntimeException());
        $this->testTx_->addCommitRule(new Exception());
        try {
            $this->exBean_->invoke(new Exception());
            $this->fail("1");
        } catch (Exception $expected) {
            var_dump($expected->getTraceAsString());
        }
        $this->assertTrue($this->testTx_->result == null);
    }

    public function testRollbackRule1() {
        $this->testTx_->addRollbackRule(new S2Container_S2RuntimeException());
        $this->testTx_->addCommitRule(new PDOException());
        try {
            $this->exBean_->invoke(new Exception());
            $this->fail("1");
        } catch (Exception $expected) {
            var_dump($expected->getTraceAsString());
        }
        $this->assertFalse($this->testTx_->result != null);
    }

    public function testRollbackRule2() {
        $this->testTx_->addRollbackRule(new S2Container_S2RuntimeException());
        $this->testTx_->addCommitRule(new PDOException());
        try {
            $this->exBean_->invoke(new Exception(""));
            $this->fail("1");
        } catch (Exception $expected) {
            var_dump($expected->getTraceAsString());
        }
        $this->assertFalse($this->testTx_->result != null);
    }

    public function testRollbackRule3() {
        $this->testTx_->addRollbackRule(new S2Container_S2RuntimeException());
        $this->testTx_->addCommitRule(new Exception());
        try {
            $this->exBean_->invoke(new PDOException());
            $this->fail("1");
        } catch (Exception $expected) {
            var_dump($expected->getTraceAsString());
        }
        $this->assertFalse($this->testTx_->result != null);
    }

    public function testRollbackRule4() {
        $this->testTx_->addRollbackRule(new S2Container_S2RuntimeException());
        $this->testTx_->addCommitRule(new Exception());
        try {
            $this->exBean_->invoke(new PDOException());
            $this->fail("1");
        } catch (Exception $expected) {
            var_dump($expected->getTraceAsString());
        }
        $this->assertFalse($this->testTx_->result != null);
    }
}
?>
