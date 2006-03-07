<?php

/**
 * @author nowel
 */
abstract class S2ActiveRecord {

    const reg_setter = '/^set[A-Z].*/';
    const reg_getter = '/^get[A-Z].*/';
    
    protected $condition;
    protected $row = array();
    private $map = null;
    
    public function __construct(S2Container_DataSource $dataSource){
        $this->condition = new S2ActiveRecordCondition($dataSource,
                                                new ReflectionClass(get_class($this)));
        $this->setupMap();
    }
    
    private function setupMap(){
        $this->map = new S2Dao_HashMap();
        $columns = $this->condition->getColumns();
        array_map(array(__CLASS__, 'initMap'), $columns);
    }
    
    private function initMap($column){
        $col = strtolower($column);
        $this->map->put($col, $column);
        $this->row[$col] = null;
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
    
    public function __set($name, $value){
        $this->row[$name] = $value;
    }
    
    public function __call($name, $prop){
        if(preg_match(self::reg_setter, $name)){
        } else if(preg_match(self::reg_getter, $name)){
        } else if($this->condition->isRecursiveMethod($name)){
        } else {
            $sql = $this->condition->getMethodSql($name);
            if($sql == null){
                return $this;
            }
            
            
        }
    }
    
    public function __clone(){
    }
    
    public function __toString(){
    }
    
    public function find(){
    }
    
    public function findFirst(){
    }
    
    public function findAll(){
        return $this->condition->getTable();
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