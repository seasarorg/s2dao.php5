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
class S2DaoTestCase extends S2Container_S2PHPUnit2TestCase {

    public function __construct($name = null) {
        if($name != null){
            parent::__construct($name);
        }
    }

    protected function assertBeanEquals($message, S2Dao_DataSet $expected, $bean) {
        $reader = new S2DaoBeanReader($bean,$this->getDatabaseMetaData());
        $this->assertEquals($message, $expected, $reader->read());
    }

    protected function assertBeanListEquals($message, S2Dao_DataSet $expected, $list) {
        $reader = new S2DaoBeanListReader($list, $this->getDatabaseMetaData());
        $this->assertEquals($message, $expected, $reader->read());
    }

    protected function getDbms() {
        return S2DaoDbmsManager::getDbms($this->getDatabaseMetaData());
    }

}
?>
