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
 * @package org.seasar.s2dao.dbms
 */
class S2Dao_Sybase extends S2Dao_Standard {

    public function getSuffix() {
        return '_sybase';
    }
    
    public function getIdentitySelectString() {
        return 'SELECT @@identity';
    }

    public function getTableSql(){
        return 'SELECT name FROM sysobjects WHERE type = \'U\'';
    }
    
    public function getTableInfoSql(){
        return 'sp_columns ' . self::BIND_TABLE;
    }
    
    public function getPrimaryKeySql(){
        return 'sp_helpconstraint ' . self::BIND_TABLE;
    }
    
}

?>
