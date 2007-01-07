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
class S2Dao_ValueTypeFactoryImpl implements S2Dao_ValueTypeFactory {

    /**
     * @type S2Container
     */
    private $container;

    /**
     * @return S2Dao_ValueType
     */
    public function getValueTypeByName($name) {
        return $container->getComponent($name);
    }

    /**
     * @return S2Dao_ValueType
     */
    public function getValueTypeByClass($phptype) {
        return S2Dao_ValueTypes::getValueType($phptype);
    }

    /**
     * @param $container S2Container
     */
    public function setContainer(S2Container $container) {
        $this->container = $container;
    }

}

?>