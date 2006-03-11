<?php

/**
 * @author nowel
 */
abstract class S2ActiveRecord {

    protected $helper;
    protected $row = array();
    private $map = null;
    
    const regex_insert_names = '/^(insert|add|create)/';
    const regex_update_names = '/^(update|modify|store)/';
    const regex_delete_names = '/^(delete|remove)/';
    
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
    
    public function __toString(){
        $str = '[TABLE]: ' . $this->helper->getTable() . PHP_EOL;
        $str .= '[COLUMNS]: ';
        $cols = array();
        foreach($this->row as $column => $value){
            $cols[] = $column . ' = ' . $value;
        }
        return $str . implode(',', $cols);
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
        } else if(preg_match(self::regex_insert_names, $name)){
            return $this->_insert();
        } else if(preg_match(self::regex_update_names, $name)){
            return $this->_update();
        } else if(preg_match(self::regex_delete_names, $name)){
            return $this->_delete();
        } else {
            $sql = $this->helper->getMethodSql($name);
            if($sql == null){
                throw new Exception();
            }
            
            if($this->helper->isRecursiveMethod($name)){
            }
        }
    }
    
    public function __clone(){
        $this->row = array();
    }
    
    public function toString(){
        return $this->__toString();
    }
    
    public function find(){
        $args = func_get_args();
        $sql = call_user_func_array(array(__CLASS__, 'createSelectSql'), $args);
        $row = $this->execute($sql)->fetch(PDO::FETCH_ASSOC);
        $row = array_change_key_case($row, CASE_LOWER);
        $cloneable = clone $this;
        $cloneable->row = $row;
        return $cloneable; 
    }
    
    public function findAll(){
        $args = func_get_args();
        $sql = call_user_func_array(array(__CLASS__, 'createSelectSql'), $args);
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
    
    public function count(){
        $sql = 'SELECT count(*) FROM ' . $this->helper->getTable();
        return (int)$this->helper->query($sql)->fetchColumn();
    }

    protected function getPlaceholders(array $row){
        $folder = array();
        foreach($row as $column => $value){
            $folder[] = $this->helper->getTable() . '.' .
                        $this->map->get($column) . ' = ?'; 
        }
        return $folder;
    }
    
    protected function createSelectSql(){
        switch(func_num_args()){
            case 0:
                $sql = $this->createSelectSqlByNormal();
                return $this->createSelectSql($sql, null);
            case 1:
                return $this->createSelectSql(func_get_arg(0), null);
            case 2:
                $args = func_get_args();
                if($args[0] instanceof S2ActiveRecord){
                    $sql = $this->createSelectSqlByCondition($args[0]);
                    return $this->createSelectSql($sql, $args[1]);
                } else if(!isset($args[0])){
                    $sql = $this->createSelectSqlByNormal();
                    return $this->createSelectSql($sql, $args[1]);
                } else {
                    $sql = $args[0];
                    $order = $args[1];
                    if(isset($order)){
                        $sql .= $this->createOrderByNormal($order);
                    }
                    return $sql;
                }
            default:
                return null;
        }
    }

    protected function createSelectSqlByNormal(){
        $sql = 'SELECT ' . implode(',', $this->helper->getColumnNames()) .
               ' FROM ' . $this->helper->getTable();
        if(0 < count($this->row)){
            $sql .= ' WHERE ';
            $sql .= implode(',', $this->getPlaceholders($this->row));
        }
        return $sql;
    }
    
    protected function createSelectSqlByCondition(S2ActiveRecord $record){
    }        

    protected function createOrderByNormal(array $orders){
        $order = array();
        foreach($orders as $column => $sort){
            $s = $this->helper->getTable() . '.' . $this->map->get($column);
            $order[] = $s . ' ' . $sort;
        }
        return ' ORDER BY ' . implode(',', $order);
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
        if(!$this->isSetPkey()){
            throw new Exception("Primery Key not set");
        }
        
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