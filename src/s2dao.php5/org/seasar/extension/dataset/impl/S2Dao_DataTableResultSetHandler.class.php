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
 * @package org.seasar.extension.dataset.impl
 */
class S2Dao_DataTableResultSetHandler implements S2Dao_ResultSetHandler {

    private $tableName;
    private $ds;

    public function __construct($tableName, S2Container_DataSource $ds) {
        $this->tableName = $tableName;
        $this->ds = $ds;
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function handle(PDOStatement $rs) {
        $table = new S2Dao_DataTableImpl($this->tableName);
        while($assoc = $rs->fetch(PDO::FETCH_ASSOC)) {
            $propertyTypes = $this->createPropertyTypes($assoc);
            $length = count($propertyTypes);
            for ($i = 0; $i < $length; ++$i) {
                $propertyName = $propertyTypes[$i]->getPropertyName();
                $table->addColumn($propertyName);
            }
            $table->setupMetaData($this->ds->getConnection());
            $this->addRow($assoc, $propertyTypes, $table);
        }
        return $table;
    }
    
    private function createPropertyTypes(array $rsmd){
        $count = count($rsmd);
        $columnNames = array_keys($rsmd);
        $propertyTypes = array();
        for($i = 0; $i < $count; ++$i){
            $propertyName = $columnNames[$i];
            $propertyTypes[] = new S2Dao_PropertyTypeImpl($propertyName, null, $columnNames[$i]);
        }
        return $propertyTypes;
    }
    
    private function addRow(array $rset, array $propertyTypes, S2Dao_DataTable $table) {
        $row = $table->addRow();
        $length = count($propertyTypes);
        for ($i = 0; $i < $length; ++$i) {
            $propertyName = $propertyTypes[$i]->getPropertyName();
            $row->setValue($propertyName, $rset[$propertyName]);
        }
        $row->setState(S2Dao_RowStates::UNCHANGED);
    }
}

?>