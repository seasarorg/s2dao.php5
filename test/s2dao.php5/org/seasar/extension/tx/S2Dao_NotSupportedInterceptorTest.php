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
class S2Dao_NotSupportedInterceptorTest extends PHPUnit2_Framework_TestCase {

    private $txBean_ = null;
    private $exBean_ = null;
    private $tm_ = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_NotSupportedInterceptorTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->tm_ = $container->getComponent("pdo.dataSource");
        $this->txBean_ = $container->getComponent("notSupportTx.TxBeanImpl");
        $this->exBean_ = $container->getComponent("notSupportTx.ExceptionBeanImpl");
    }

    protected function tearDown() {
        $this->tm_ = null;
        $this->txBean_ = null;
        $this->exBean_ = null;
    }

    public function testInvoke() {
        $this->assertFalse($this->txBean_->hasTransaction());
    }

    public function testInvokeTx() {
        $this->assertFalse($this->txBean_->hasTransaction());
    }

    public function testInvokeExceptionTx() {
        try {
            $this->exBean_->invoke();
            $this->fail("1");
        } catch (Exception $e) {
            var_dump($e->getTraceAsString());
        }
    }
}
?>
