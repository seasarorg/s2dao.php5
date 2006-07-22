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
class Search1Test extends PHPUnit2_Framework_TestCase {
    
    private $dao = null;
    
    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("Search1Test");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dao = $container->getComponent("search1.SearchDao");
    }

    protected function tearDown() {
        $this->dao = null;
    }
    
    public function testSearch1(){
        $this->assertTrue($this->dao->get1List() instanceof S2Dao_ArrayList);
    }
    
    public function testSearch2Query(){
        $this->assertEquals(1, $this->dao->get2List()->size());
        $dept = $this->dao->get2List()->get(0);
        $this->assertEquals("30", $dept->getDeptno());
    }
    
    public function testSearch3Query(){
        $this->assertNotEquals($this->dao->get3List(), $this->dao->get1List());        
    }
    
    public function testSearch4Query(){
        $this->assertEquals($this->dao->get4List(), $this->dao->get2List());
    }
    
    public function testSearch5Query(){
        try {
            $this->dao->get5List();
            $this->fail("Not Supported start with 'WHERE'");
        } catch(Exception $e){
            var_dump($e->getMessage());
        }
    }
    
    public function testSearch6Query(){
        $this->assertEquals(2, $this->dao->get6List()->size());
    }
    
    public function testSearch7Query(){
        try {
            $this->dao->get7List();
            $this->fail("Not Supported start with 'WHERE'");
        } catch(Exception $e){
            var_dump($e->getMessage());
        }
    }
}

?>