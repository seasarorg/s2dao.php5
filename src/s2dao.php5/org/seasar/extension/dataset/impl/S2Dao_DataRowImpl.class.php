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
class S2Dao_S2Dao_DataRowImpl implements S2Dao_S2Dao_DataRow {

    private $table_;

    private $values_;

    private $state_ = S2Dao_RowStates::UNCHANGED;

    public function __construct(S2Dao_DataTable $table) {
        $this->values_ = new S2Dao_HashS2Dao_HashMap();
        $this->table_ = $table;
        $this->initValues();
    }

    private function initValues() {
        for ($i = 0; $i < $this->table_->getColumnSize(); ++$i) {
            $this->values_->put($this->table_->getColumnName($i), null);
        }
    }

    public function getValue($index) {
        return $this->values_->get($index);
    }

    public function getValue($columnName) {
        $column = $this->table_->getColumn($columnName);
        return $this->values_->get($column->getColumnIndex());
    }

    public function setValue($columnName, $value) {
        $column = $this->table_->getColumn($columnName);
        $this->values_->put($columnName, $column->convert($value));
        $this->modify();
    }

    public function setValue($index, $value) {
        $column = $this->table_->getColumn($index);
        $this->values_->set($index, $column->convert($value));
        $this->modify();
    }

    private function modify() {
        if ($this->state_ === S2Dao_RowStates::UNCHANGED) {
            $this->state_ = S2Dao_RowStates::MODIFIED;
        }
    }

    public function remove() {
        $this->state_ = S2Dao_RowStates::REMOVED;
    }

    public function getTable() {
        return $this->table_;
    }

    public function getState() {
        return $this->state_;
    }

    public function setState(S2Dao_RowState $state) {
        $this->state_ = $state;
    }

    public function toString() {
        $buf = '';
        $buf .= '{';
        for ($i = 0; $i < $this->values_->size(); ++$i) {
            $buf .= $this->getValue($i);
            $buf .= ', ';
        }
        $buf = preg_replace('/(, )/', '', $buf);
        $buf .= '}';
        return $buf;
    }

    public function equals($o) {
        if ($o === $this) {
            return true;
        }
        if (!($o instanceof S2Dao_DataRow)) {
            return false;
        }
        $other = $o;
        for ($i = 0; $i < $this->table_->getColumnSize(); ++$i) {
            $columnName = $this->table_->getColumnName($i);
            $value = $this->values_->get($i);
            $otherValue = $other->getValue($columnName);
            $ct = S2Doa_ColumnTypes::getColumnType($value);
            if ($ct->equals($value, $otherValue)) {
                continue;
            }
            return false;
        }
        return true;
    }

    public function copyFrom($source) {
        if ($source instanceof S2Dao_HashMap) {
            $this->copyFromS2Dao_HashMap($source);
        } else if ($source instanceof S2Dao_DataRow) {
            $this->copyFromRow($source);
        } else {
            $this->copyFromBean($source);
        }

    }

    private function copyFromS2Dao_HashMap($source) {
        for ($i = $source->keySet()->iterator(); $i->valid(); $i->next()) {
            $columnName = $i->current();
            if ($this->table_->hasColumn($columnName)) {
                $value = $source->get($columnName);
                $this->setValue($columnName, $this->convertValue($value));
            }
        }
    }

    private function copyFromRow(S2Dao_DataRow $source) {
        for ($i = 0; $i < $source->getTable()->getColumnSize(); ++$i) {
            $columnName = $source->getTable()->getColumnName($i);
            if ($this->table_->hasColumn($columnName)) {
                $value = $source->getValue($i);
                $this->setValue($columnName, $this->convertValue($value));
            }
        }
    }

    private function copyFromBean($source) {
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc(new ReflectionClass($source));
        for ($i = 0; $i < $this->table_->getColumnSize(); ++$i) {
            $columnName = $this->table_->getColumnName($i);
            $propertyName = str_replace('_', '', $columnName);
            if ($beanDesc->hasPropertyDesc($propertyName)) {
                $pd = $beanDesc->getPropertyDesc($propertyName);
                $value = $pd->getValue($source);
                $this->setValue($columnName, $this->convertValue($value));
            }
        }
    }

    private function convertValue($value) {
        if ($value == null) {
            return null;
        }
        $columnType = S2Dao_ColumnTypes::getColumnType(new ReflectionClass($value));
        return $columnType->convert($value, null);
    }
}

?>