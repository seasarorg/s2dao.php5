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
class S2Dao_DataColumnImpl implements S2Dao_DataColumn {

    private $columnName;
    private $columnType;
    private $columnIndex;
    private $primaryKey = false;
    private $writable = true;
    private $formatPattern;

    public function __construct($columnName, $columnType, $columnIndex) {
        $this->setColumnName($columnName);
        $this->setColumnType($columnType);
        $this->setColumnIndex($columnIndex);
    }

    public function getColumnName() {
        return $this->columnName;
    }

    public function setColumnName($columnName) {
        $this->columnName = $columnName;
    }

    public function getColumnType() {
        return $this->columnType;
    }

    public function setColumnType($columnType) {
        $this->columnType = $columnType;
    }

    public function getColumnIndex() {
        return $this->columnIndex;
    }

    public function setColumnIndex($columnIndex) {
        $this->columnIndex = $columnIndex;
    }

    public function isPrimaryKey() {
        return $this->primaryKey;
    }

    public function setPrimaryKey($primaryKey) {
        $this->primaryKey = $primaryKey;
    }

    public function isWritable() {
        return $this->writable;
    }

    public function setWritable($writable) {
        $this->writable = $writable;
    }

    public function getFormatPattern() {
        return $this->formatPattern;
    }

    public function setFormatPattern($formatPattern) {
        $this->formatPattern = $formatPattern;
    }

    public function convert($value) {
        return $value;
        //return $this->columnType->convert($value, $this->formatPattern);
    }
}