<?php

/**
 * @author nowel
 */
class S2Dao_ArrayList extends ArrayObject {

    public function __construct($object = null){
        if($object == null){
            parent::__construct();
        } else {
            parent::__construct($object);
        }
    }
    
    public function size(){
        return $this->count();
    }
    
    public function isEmpty(){
        return count($this) == 0;
    }
    
    public function contains($object){
        return in_array($object, $this->getArrayCopy(), true);
    }
    
    public function get($index){
        return $this->offsetGet($index);
    }
	
    public function set($index, $object){
        $this->offsetSet($index, $object);
    }
    	
    public function add($index, $object = null){
        if($object == null){
            $this->append($index);
        } else {
            $this->offsetSet($index, $object);
        }
    }
    
    public function addAll(ArrayObject $list){
        foreach($list->toArray() as $value){
            $this->append($value);
        }
    }
    
    public function remove($index){
        $this->offsetUnset($index);
    }
    
    public function iterator(){
        return $this->getIterator();
    }
    
    public function toArray(){
        return $this->getArrayCopy();
    }
}

?>
