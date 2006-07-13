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
class CdTxManagerTest extends PHPUnit2_Framework_TestCase {
    
    private $manager = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_AbstractAnnotationReaderTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->manager = $container->getComponent("stx.CdTxManager");
    }

    protected function tearDown() {
        $this->manager = null;
    }
    
    public function testRequiredInsert(){
        echo "requiredInsert start" . PHP_EOL;
        try{
            $this->manager->requiredInsert();
        }catch(Exception $e){
            var_dump($e->getMessage());
        }
        echo "requiredInsert end" . PHP_EOL;
    }
    
    public function testRequiredNewInsert(){
        echo "requiresNewInsert start" . PHP_EOL;
        try{
            $this->manager->requiresNewInsert();
        }catch(Exception $e){
            var_dump($e->getMessage());
        }
        echo "requiresNewInsert end" . PHP_EOL;
    }

    public function testMandatoryInsert(){
        echo "mandatoryInsert start" . PHP_EOL;
        try{
            $this->manager->mandatoryInsert();
        }catch(Exception $e){
            var_dump($e->getMessage());
        }
        echo "mandatoryInsert end" . PHP_EOL;
    }

    public function testGetAll(){
        echo "getAll start" . PHP_EOL;
        try{
            var_dump($this->manager->getAll());
        }catch(Exception $e){
            var_dump($e->getMessage());
        }
        echo "getAll end" . PHP_EOL;
    }
    
    public function testDelete(){
        try{
            $this->manager->delete();
        }catch(Exception $e){
            var_dump($e->getMessage());
        }
    }
}

?>