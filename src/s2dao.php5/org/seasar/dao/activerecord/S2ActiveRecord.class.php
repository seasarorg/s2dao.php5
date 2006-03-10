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
        if($this->__isset($element)){
            $column = strtolower($element);
            return $this->row[$column];
        } else {
            return null;
        }
    }
    
    public function __set($element, $value){
        $column = strtolower($element);
        $this->row[$column] = $value;
    }
    
    public function __call($name, $prop){
        $type = '';
        $attr = '';
        if(3 < strlen($name)){ 
            $type = substr($name, 0, 3);
            $attr = substr($name, 3);
        }
        if($type == 'set'){
            $this->__set($attr, $prop[0]);
        } else if($type == 'get'){
            return $this->__get($attr);
        } else if($this->helper->isRecursiveMethod($name)){
        } else {
            $sql = $this->helper->getMethodSql($name);
            if($sql == null){
                return $this;
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
    
    protected function getPlaceholders(array $row){
        $folder = array();
        foreach($row as $column => $value){
            $folder[] = $this->helper->getTable() . '.' .
                        $this->map->get($column) . " = ?"; 
        }
        return $folder;
    }
    
    public function find(){
    }
    
    public function findFirst(){
    }
    
    public function findAll(){
        $sql = 'SELECT ' . implode(',', $this->helper->getColumnNames()) .
               ' FROM ' . $this->helper->getTable();
        
        if(0 < count($this->row)){
            $sql .= ' WHERE ';
            $sql .= implode(',', $this->getPlaceholders($this->row));
        }
        $stmt = $this->helper->prepare($sql);
        $this->helper->bindArgs($stmt, $this->row);
        $stmt->execute();
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $retVal = array();
        foreach($rows as $index => $row){
            $row = array_change_key_case($row, CASE_LOWER);
            $cloneable = clone $this;
            $cloneable->row = $row;
            $retVal[$index] = $cloneable;
        }
        return new S2Dao_ArrayList($retVal);
    }
    
    public function save(){
    }
    
    public function delete(){
    }
    
    public function count(){
    }
    
    private function insert(){
    }
    
    private function update(){
    }
    
}

?>