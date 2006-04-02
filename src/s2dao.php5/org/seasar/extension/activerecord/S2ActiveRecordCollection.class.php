<?php

/**
 * @author nowel
 */
class S2ActiveRecordCollection implements Iterator {
    
    protected $rows = array();
    private $key = 0;
    
    public function __construct(array $rows) {
        $this->rows = $rows;
    }

    public function current() {
        return $this->rows[$this->key];
    }

    public function key() {
        return $this->key;
    }

    public function next() {
        $this->key++;
    }
    
    public function prev(){
        if(0 < $this->key){
            $this->key--;
        }
    }

    public function rewind() {
        $this->key = 0;
    }
    
    public function count(){
        return count($this->rows);
    }

    public function valid(){
        return $this->rows[$this->key] instanceof S2ActiveRecord;
    }
    
    public function toArray(){
        $ret = array();
        foreach($this->rows as $row){
            $ret[] = $row->toArray();
        }
        return $ret;
    }
}
?>