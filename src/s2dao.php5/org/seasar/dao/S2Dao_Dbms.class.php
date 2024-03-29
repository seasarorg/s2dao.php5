<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
 * @package org.seasar.s2dao
 */
interface S2Dao_Dbms {
    
    const BIND_TABLE = ':TABLE';
    
    const BIND_COLUMN = ':COLUMN';
    
    const BIND_DB = ':DB';
    
    const BIND_SCHEME = ':SCHEME';
    
    const BIND_CATALOG = ':CATALOG';
    
    const BIND_NAME = ':NAME';
    
    public function getAutoSelectSql(S2Dao_BeanMetaData $beanMetaData, $joinData = array());
    
    public function getSuffix();
    
    public function getIdentitySelectString();
    
    public function getSequenceNextValString($sequenceName);
    
    public function getTableSql();
    
    public function getTableInfoSql();
    
    public function getPrimaryKeySql();
    
    public function getProcedureNamesSql();
    
    public function getProcedureInfoSql();
    
    public function getLimitOffsetSql();
    
    public function isSelfGenerate();
    
    public function getBaseSql($st);
}
?>
