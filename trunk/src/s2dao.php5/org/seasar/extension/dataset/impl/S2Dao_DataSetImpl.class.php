<?php

/**
 * @author nowel
 */
class S2Dao_DataSetImpl implements S2Dao_DataSet {
    
    private $tables_ = null;

    public function __construct() {
        $this->tables_ = new S2Dao_HashMap();
    }

    public function getTableSize() {
        return $this->tables_->size();
    }
    
    public function getTableName($index) {
        return $this->getTable($index)->getTableName();
    }

    public function getTable($table) {
        if(is_integer($table)){
            return $this->tables_->get($table);
        } else {
            $table = $this->tables_->get($table);
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
            $this->tables_->put($table->getTableName(), $table);
            return $table;
        }
    }

    public function removeTable($table) {
        if($table instanceof S2Dao_DataTable){
            return $this->removeTable($table->getTableName());
        } else if(is_string($table)){
            $table = $this->tables_->remove($table);
            if ($table == null) {
                throw new S2Dao_TableNotFoundRuntimeException($table);
            }
            return $table;
        } else {
            return $this->tables_->remove($table);
        }
    }

    public function toString() {
        $buf = '';
        for ($i = 0; $i < $this->getTableSize(); ++$i) {
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