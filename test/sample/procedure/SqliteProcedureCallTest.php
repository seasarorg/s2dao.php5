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
class SqliteProcedureCallTest extends PHPUnit2_Framework_TestCase {
    
    private $dao = null;
    
    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("SqliteProcedureCallTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dao = $container->getComponent("procedure.SampleProcedureDao");
    }

    protected function tearDown() {
        $this->dao = null;
    }
    
    public function testSalesTax(){
        var_dump($this->dao->getSalesTax(200));
        $this->assertEquals(40, (int)$this->dao->getSalesTax(200));
    }
    
    public function testSalesTax2(){
        $this->assertEquals(40, (int)$this->dao->getSalesTax2(200));
    }
    
    public function testSalesTax3(){
        $sales = 200;
        $ret = $this->dao->getSalesTax3($sales);
        var_dump($ret);
        // not support inout param
        //$this->assertEquals(40, $sales);
    }
    
    public function testSalesTax4(){
        $tax = null;
        $total = null;
        $map = $this->dao->getSalesTax4Map(200, $tax, $total);
        // not support mixin inout param
        //$this->assertEquals(40, $tax);
    }

}

?>