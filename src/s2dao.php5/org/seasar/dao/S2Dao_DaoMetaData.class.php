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
interface S2Dao_DaoMetaData {

    const BEAN = 'BEAN';
    const QUERY = 'QUERY';
    const FILE = 'FILE';

    public function getBeanClass();
    public function getBeanMetaData();
    public function hasSqlCommand($methodName);
    public function getSqlCommand($methodName);
    public function createFindCommand($query);
    public function createFindArrayCommand($query);
    public function createFindBeanCommand($query);
    public function createFindYamlCommand($query);
    public function createFindJsonCommand($query);
    public function createFindObjectCommand($query);
}
?>
