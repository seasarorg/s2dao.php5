<?php

/**
 * @author nowel
 */
abstract class S2ActiveRecord {

    protected $helper;
    protected $row = array();
    private $map = null;
    
    public function __construct(S2Container_DataSource $dataSource){
        $this->helper = new S2ActiveRecordHelper($dataSource,
                                                new ReflectionClass(get_class($this)));
        $this->setupMap();
    }
    
    private function setupMap(){
        $this->map = new S2Dao_HashMap();
        $columns = $this->helper->getColumnNames();
        array_map(array(__CLASS__, 'initMap'), $columns);
    }
    
    private function initMap($column){
        $col = strtolower($column);
        $this->map->put($col, $column);
    }   
    
    public function __isset($element){
        return isset($this->row[$element]);
    }
    
    public function __unset($element){
        unset($this->row[$element]); 
    }
    
    public function __get($element){
        $column = strtolower($element);
        if($this->__isset($column)){
            return $this->row[$column];
        } else {
            return null;
        }
    }
    
    public function __set($element, $value){
        $column = strtolower($element);
        $this->row[$column] = $value;
    }
    
    public function __call($name, $param){
        $type = '';
        $attr = '';
        if(3 < strlen($name)){ 
            $type = substr($name, 0, 3);
            $attr = substr($name, 3);
        }
        if($type == 'set'){
            $this->__set($attr, $param[0]);
        } else if($type == 'get'){
            return $this->__get($attr);
        } else if($this->helper->isRecursiveMethod($name)){
        } else {
            $sql = $this->helper->getMethodSql($name);
            if($sql == null){
                throw new Exception();
            }
        }
    }
    
    public function __clone(){
        $this->row = array();
    }
    
    public function __toString(){
        $str = 'TABLE: ' . $this->helper->getTable() . PHP_EOL;
        $str .= 'COLUMNS: ' . PHP_EOL;
        foreach($this->row as $column => $value){
            $str .= $column . " = " . $value . PHP_EOL;
        }
        return $str;
    }
    
    public function toString(){
        return $this->__toString();
    }
    
    protected function getPlaceholders(array $row){
        $folder = array();
        foreach($row as $column => $value){
            $folder[] = $this->helper->getTable() . '.' .
                        $this->map->get($column) . " = ?"; 
        }
        return $folder;
    }
    
    public function find(){
        $sql = $this->getNormalSelectSql();
        $row = $this->execute($sql)->fetch(PDO::FETCH_ASSOC);
        $row = array_change_key_case($row, CASE_LOWER);
        $cloneable = clone $this;
        $cloneable->row = $row;
        return $cloneable; 
    }
    
    public function findAll(){
        $sql = $this->getNormalSelectSql();
        $stmt = $this->execute($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $retVal = array();
        foreach($rows as $index => $row){
            $row = array_change_key_case($row, CASE_LOWER);
            $cloneable = clone $this;
            $cloneable->row = $row;
            $retVal[$index] = $cloneable;
        }
        return new S2ActiveRecordCollection($retVal);
    }
    
    public function save(){
        if($this->isSetPkey()){
            return $this->_update();
        } else {
            return $this->_insert();
        }
    }
    
    public function delete(){
        if(!$this->isSetPkey()){
            throw new Exception("Primery Key not set");
        }
        return $this->_delete();
    }
    
    protected function isSetPkey(){
        $pkeys = $this->helper->getPrimaryKeyNames();
        $bool = true;
        foreach($pkeys as $pk){
            if($this->__get($pk) == null){
                $bool = false;
            }
        }
        return $bool;
    }
    
    protected function execute($sql){
        $stmt = $this->helper->prepare($sql);
        $this->helper->bindArgs($stmt, $this->row);
        $stmt->execute();
        return $stmt;
    }
    
    protected function getNormalSelectSql(){
        $sql = 'SELECT ' . implode(',', $this->helper->getColumnNames()) .
               ' FROM ' . $this->helper->getTable();
        if(0 < count($this->row)){
            $sql .= ' WHERE ';
            $sql .= implode(',', $this->getPlaceholders($this->row));
        }
        return $sql;
    }
    
    private function _insert(){
        $sql = 'INSERT INTO ' . $this->helper->getTable();
        $cols = array();
        $vals = array();
        foreach($this->row as $column => $value){
            $cols[] = $this->map->get($column);
            $vals[] = '?';
        }
        $sql .= '(' . implode(',', $cols) . ')';
        $sql .= ' VALUES (' . implode(',', $vals) . ')';
        return $this->execute($sql) ? true : false;
    }
    
    private function _update(){
        $sql = 'UPDATE ' . $this->helper->getTable();
        $sql .= ' SET ';
        $sql .= implode(',', $this->getPlaceholders($this->row));
        
        $pkeys = $this->helper->getPrimaryKeyNames();
        $where = array();
        $bind = array();
        foreach($pkeys as $index => $pk){
            $value = $this->__get($pk);
            if($value != null){
                $where[] = $this->helper->getTable() . '.' . $pk . ' = ?';
                $bind[] = $value;
            }
        }
        $sql .= ' WHERE ' . implode(',', $where);
        $stmt = $this->helper->prepare($sql);
        $this->helper->bindArgs($stmt, $this->row);
        $l = count($this->row) + 1;
        foreach($bind as $index => $value){
            $phptype = gettype($value);
            $stmt->bindValue($l + $index, $value, S2Dao_PDOType::gettype($phptype));
        }
        $stmt->execute();
        return $stmt->rowCount();
    }
    
    
    private function _delete(){
        $sql = 'DELETE FROM ' . $this->helper->getTable();
        $pkeys = $this->helper->getPrimaryKeyNames();
        $where = array();
        $bind = array();
        foreach($pkeys as $index => $pk){
            $value = $this->__get($pk);
            if($value != null){
                $where[] = $this->helper->getTable() . '.' . $pk . ' = ?';
                $bind[] = $value;
            }
        }
        $sql .= ' WHERE ' . implode(',', $where);
        $stmt = $this->helper->prepare($sql);
        $this->helper->bindArgs($stmt, $this->row);
        $l = count($this->row) + 1;
        foreach($bind as $index => $value){
            $phptype = gettype($value);
            $stmt->bindValue($l + $index, $value, S2Dao_PDOType::gettype($phptype));
        }
        $stmt->execute();
        return $stmt->rowCount();
    }
}

?>