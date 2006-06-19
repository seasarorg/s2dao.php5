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
class S2Dao_DataTableImpl implements S2Dao_DataTable {

    private $tableName;
    private $rows;
    private $removedrows;
    private $columns;
    private $hasMetaData = false;

    public function __construct($tableName) {
        $this->rows = new S2Dao_ArrayList();
        $this->removedrows = new S2Dao_ArrayList();
        $this->columns = new S2Dao_HashMap();
        $this->setTableName($tableName);
    }

    public function getTableName() {
        return $this->tableName;
    }

    public function setTableName($tableName) {
        $this->tableName = $tableName;
    }

    public function getRowSize() {
        return $this->rows->size();
    }

    public function getRow($index) {
        return $this->rows->get($index);
    }

    public function addRow() {
        $row = new S2Dao_DataRowImpl($this);
        $rows->add($row);
        $row->setState(S2Dao_RowStates::CREATED);
        return $row;
    }

    public function getRemovedRowSize() {
        return $this->removedrows->size();
    }

    public function getRemovedRow($index) {
        return $this->removedrows->get($index);
    }

    public function removeRows() {
        for ($i = 0; $i < $this->rows->size();) {
            $row = $this->getRow($i);
            if ($row->getState()->equals(S2Dao_RowStates::REMOVED)) {
                $this->removedrows->add($row);
                $this->rows->remove($i);
            } else {
                ++$i;
            }
        }
        return $this->removedrows->toArray();
    }

    public function getColumnSize() {
        return $this->columns->size();
    }

    public function getColumn($index) {
        return $this->columns->get($index);
    }

    public function getColumn($columnName) {
        $column = $this->getColumn0($columnName);
        if ($column == null) {
            throw new S2Dao_ColumnNotFoundRuntimeException($this->tableName, $columnName);
        }
        return $column;
    }
    
    private function getColumn0($columnName) {
        $column = $this->columns->get($columnName);
        if ($column == null) {
            $name = str_replace('_', '', $columnName);
            $column = $this->columns->get($name);
            if ($column == null) {
                for ($i = 0; $i < $this->columns->size(); ++$i) {
                    $key = $this->columns->getKey($i);
                    $key2 = str_replace('_', '', $key);
                    if (strcasecmp($key2, $name) == 0) {
                        $column = $this->columns->get($i);
                        break;
                    }
                }
            }
        }
        return $column;
    }

    public function hasColumn($columnName) {
        return $this->getColumn0($columnName) !== null;
    }

    public function getColumnName($index) {
        return $this->getColumn($index)->getColumnName();
    }

    public function getColumnType($index) {
        return $this->getColumn($index)->getColumnType();
    }

    public function getColumnType($columnName) {
        return $this->getColumn($columnName)->getColumnType();
    }

    public function addColumn($columnName, S2Dao_ColumnType $columnType = null) {
        if($columnType === null){
            return $this->addColumn($columnName, S2Dao_ColumnTypes::OBJECT);
        }            
        $column = new S2Dao_DataColumnImpl($columnName, $columnType, $this->columns->size());
        $this->columns->put($columnName, $column);
        return $column;
    }

    public function hasMetaData() {
        return $this->hasMetaData;
    }

    public function setupMetaData($dbMetaData) {
        $primaryKeySet = S2Dao_DatabaseMetaDataUtil::getPrimaryKeySet($dbMetaData, $this->tableName);
        $columnMap = S2Dao_DatabaseMetaDataUtil::getColumnMap($dbMetaData, $this->tableName);
        for ($i = 0; $i < $this->getColumnSize(); ++$i) {
            $column = $this->getColumn($i);
            if ($primaryKeySet->contains($column->getColumnName())) {
                $column->setPrimaryKey(true);
            } else {
                $column->setPrimaryKey(false);
            }
            if ($columnMap->containsKey($column->getColumnName())) {
                $column->setWritable(true);
                $cd = $columnMap->get($column->getColumnName());
                $column->setColumnType(S2Dao_ColumnTypes::getColumnType($cd->getSqlType()));
            } else {
                $column->setWritable(false);
            }
        }
        $this->hasMetaData = true;
    }

    public function setupColumns(ReflectionClass $beanClass) {
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($beanClass);
        for ($i = 0; $i < $beanDesc->getPropertyDescSize(); ++$i) {
            $pd = $beanDesc->getPropertyDesc($i);
            $this->addColumn($pd->getPropertyName(),
                            S2Dao_ColumnTypes::getColumnType($pd->getPropertyType()));
        }
    }

    public function copyFrom($source) {
        if ($source instanceof S2Dao_ArrayList) {
            $this->copyFromList($source);
        } else {
            $this->copyFromBeanOrMap($source);
        }

    }

    private function copyFromList(S2Dao_ArrayList $source) {
        for ($i = 0; $i < $source->size(); ++$i) {
            $row = $this->addRow();
            $row->copyFrom($source->get($i));
            $row->setState(S2Dao_RowStates::UNCHANGED);
        }
    }

    private function copyFromBeanOrMap($source) {
        $row = $this->addRow();
        $row->copyFrom($source);
        $row->setState(S2Dao_RowStates::UNCHANGED);
    }

    public function toString() {
        $buf = '';
        $buf .= $this->tableName;
        $buf .= ':';
        for ($i = 0; $i < $this->columns->size(); ++$i) {
            $buf .= $this->getColumnName($i);
            $buf .= ', ';
        }
        $buf = preg_replace('/(, )/', '', $buf);
        $buf .= PHP_EOL;
        for ($i = 0; $i < $this->rows->size(); ++$i) {
            $buf .= $this->getRow($i) . PHP_EOL;
        }
        return $buf;
    }

    public function equals($o) {
        if ($o === $this) {
            return true;
        }
        if (!($o instanceof S2Dao_DataTable)) {
            return false;
        }
        $other = $o;
        if ($this->getRowSize() != $other->getRowSize()) {
            return false;
        }
        for ($i = 0; $i < $this->getRowSize(); ++$i) {
            if (!$this->getRow($i)->equals($other->getRow($i))) {
                return false;
            }
        }
        if ($this->getRemovedRowSize() != $other->getRemovedRowSize()) {
            return false;
        }
        for ($i = 0; $i < $this->getRemovedRowSize(); ++$i) {
            if (!$this->getRemovedRow($i)->equals($other->getRemovedRow($i))) {
                return false;
            }
        }
        return true;
    }
}

?>