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
interface S2Dao_DataTable {

    public function getTableName();
    
    public function setTableName($tableName);
    
    public function getRowSize();
    
    public function getRow($index);
    
    public function addRow();
    
    public function getRemovedRowSize();
    
    public function getRemovedRow($index);

    public function removeRows();
    
    public function getColumnSize();
    
    public function getColumn($column);
    
    public function hasColumn($columnName);
    
    public function getColumnName($index);
    
    public function getColumnType($column);
    
    public function addColumn($columnName, S2Dao_ColumnType $columnType);

    public function hasMetaData();
    
    public function setupMetaData(PDO $pdo);
    
    public function setupColumns(ReflectionClass $beanClass);
    
    public function copyFrom($source);
}
?>