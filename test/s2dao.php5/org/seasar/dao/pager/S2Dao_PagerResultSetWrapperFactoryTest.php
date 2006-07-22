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
class S2Dao_PagerResultSetWrapperFactoryTest extends PHPUnit2_Framework_TestCase {

    private $dao = null;

    public static function main() {
        $suite  = new PHPUnit2_Framework_TestSuite("S2Dao_PagerResultSetWrapperFactoryTest");
        $result = PHPUnit2_TextUI_TestRunner::run($suite);
    }

    protected function setUp() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $this->dao = $container->getComponent('pager.empPager');
        $this->dto = new S2Dao_DefaultPagerCondition();
    }

    protected function tearDown() {
        $this->dao = null;
    }

    public function testCreate() {
        $refDaoClass = new ReflectionClass('EmployeePagerDao');
        $refDaoMethod = $refDaoClass->getMethod('getAllByPagerConditionList');
 
        $invocation = new S2Container_S2MethodInvocationImpl($this->dao,
                                                             $refDaoClass,
                                                             $refDaoMethod,
                                                             array($this->dto),
                                                             array());

        $wrapper = S2Dao_PagerResultSetWrapperFactory::create($invocation);
        $isBasicResultSetWrapper = ($wrapper instanceof S2Dao_PagerBasicResultSetWrapper);
        $this->assertEquals($isBasicResultSetWrapper, true);
    }
    
    public function testCreateArray() {
        $refDaoClass = new ReflectionClass('EmployeePagerDao');
        $refDaoMethod = $refDaoClass->getMethod('getAllByPagerConditionArray');
        
        $invocation = new S2Container_S2MethodInvocationImpl($this->dao,
                                                             $refDaoClass,
                                                             $refDaoMethod,
                                                             array($this->dto),
                                                             array());
        $wrapper = S2Dao_PagerResultSetWrapperFactory::create($invocation);
        $isBasicResultSetWrapper = ($wrapper instanceof S2Dao_PagerBasicResultSetWrapper);
        $this->assertEquals($isBasicResultSetWrapper, true);
    }

    public function testCreateYaml() {
        $refDaoClass = new ReflectionClass('EmployeePagerDao');
        $refDaoMethod = $refDaoClass->getMethod('getAllByPagerConditionYaml');

        $invocation = new S2Container_S2MethodInvocationImpl($this->dao,
                                                             $refDaoClass,
                                                             $refDaoMethod,
                                                             array($this->dto),
                                                             array());

        $wrapper = S2Dao_PagerResultSetWrapperFactory::create($invocation);
        $isYamlResultSetWrapper = ($wrapper instanceof S2Dao_PagerYamlResultSetWrapper);
        $this->assertEquals($isYamlResultSetWrapper, true);
    }

    public function testCreateJson() {
        $refDaoClass = new ReflectionClass('EmployeePagerDao');
        $refDaoMethod = $refDaoClass->getMethod('getAllByPagerConditionJson');

        $invocation = new S2Container_S2MethodInvocationImpl($this->dao,
                                                             $refDaoClass,
                                                             $refDaoMethod,
                                                             array($this->dto),
                                                             array());

        $wrapper = S2Dao_PagerResultSetWrapperFactory::create($invocation);
        $isJsonResultSetWrapper = ($wrapper instanceof S2Dao_PagerJsonResultSetWrapper);
        $this->assertEquals($isJsonResultSetWrapper, true);
    }
}

?>