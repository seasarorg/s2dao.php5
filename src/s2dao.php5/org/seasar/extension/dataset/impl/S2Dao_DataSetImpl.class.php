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
class S2Dao_DataSetImpl implements S2Dao_DataSet {
    
    private $tables = null;

    public function __construct() {
        $this->tables = new S2Dao_HashMap();
    }

    public function getTableSize() {
        return $this->tables->size();
    }
    
    public function getTableName($index) {
        return $this->getTable($index)->getTableName();
    }

    /**
     * @throws S2Dao_TableNotFoundRuntimeException
     */
    public function getTable($table) {
        if(is_integer($table)){
            $values = array_values($this->tables->toArray());
            return $values[$table];
        } else {
            $table = $this->tables->get($table);
            if ($table == null) {
                throw new S2Dao_TableNotFoundRuntimeException($table);
            }
        }
        return $table;
    }

    public function addTable($table) {
        if(is_string($table)){
            return $this->addTable(new S2Dao_DataTableImpl($table));
        } else {
            $this->tables->put($table->getTableName(), $table);
            return $table;
        }
    }

    /**
     * @throws S2Dao_TableNotFoundRuntimeException
     */
    public function removeTable($table) {
        if($table instanceof S2Dao_DataTable){
            return $this->removeTable($table->getTableName());
        } else if(is_string($table)){
            $table = $this->tables->remove($table);
            if ($table === null) {
                throw new S2Dao_TableNotFoundRuntimeException($table);
            }
            return $table;
        } else if(is_integer($table)){
            $keys = array_keys($this->tables->toArray());
            return $this->removeTable($keys[$table]);
        } else {
            return $this->tables->remove($table);
        }
    }

    public function toString() {
        $buf = '';
        $size = $this->getTableSize();
        for ($i = 0; $i < $size; ++$i) {
            $buf .= $this->getTable($i);
            $buf .= PHP_EOL;
        }
        return $buf;
    }

    public function equals($o) {
        if (!($o instanceof S2Dao_DataSet)) {
            return false;
        }
        if(!is_object($o)){
            return false;
        }
        if ($o == $this) {
            return true;
        }
        if ($this->getTableSize() != $o->getTableSize()) {
            return false;
        }
        for ($i = 0; $i < $this->getTableSize(); ++$i) {
            if (!$this->getTable($i)->equals($o->getTable($i))) {
                return false;
            }
        }
        return true;
    }
}

?>