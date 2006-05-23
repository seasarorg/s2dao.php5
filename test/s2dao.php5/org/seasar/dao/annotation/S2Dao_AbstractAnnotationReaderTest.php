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

require_once dirname(__FILE__) . "/Dao.php";
require_once dirname(__FILE__) . "/Bean.php";

/**
 * @author nowel
 */
class S2Dao_AbstractAnnotationReaderTest extends PHPUnit2_Framework_TestCase {

    private $beanAnnotation = null;
    private $daoAnnotation = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_AbstractAnnotationReaderTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $factory = new S2Dao_FieldAnnotationReaderFactory();
        $beanClass = new ReflectionClass("HogeBean");
        $daoClass = new ReflectionClass("HogeDao");
        $daoDesc = S2Container_BeanDescFactory::getBeanDesc($daoClass);

        $this->daoAnnotationReader = $factory->createDaoAnnotationReader($daoDesc);
        $this->beanAnnotationReader = $factory->createBeanAnnotationReader($beanClass);
    }

    protected function tearDown() {
        $this->beanAnnotationReader = null;
        $this->daoAnnotationReader = null;
    }

    public function testEachClassExtendsAbstractAnnotationReader(){
        $this->assertTrue($this->beanAnnotationReader instanceof S2Dao_AbstractAnnotationReader);
        $this->assertTrue($this->daoAnnotationReader instanceof S2Dao_AbstractAnnotationReader);
        $this->assertNotEquals($tis->beanAnnotationReader, $this->daoAnnotationReader);
    }
    
    public function testBeanAnnotationNormalCall(){
        $this->assertNull($this->beanAnnotationReader->getVersionNoPropertyNameAnnotation());
        $table = $this->beanAnnotationReader->getTableAnnotation();
        $this->assertNotNull($table);
        $this->assertSame("HogeTable", $table);
    }
    
    public function testDaoAnnotationNormalCall(){
        $bean = $this->daoAnnotationReader->getBeanClass();
        $this->assertNotNull($bean);
        $this->assertTrue($bean == new ReflectionClass("HogeBean"));
        $this->assertNotSame($bean, new ReflectionClass("HogeBean"));
    }
    
}

?>
