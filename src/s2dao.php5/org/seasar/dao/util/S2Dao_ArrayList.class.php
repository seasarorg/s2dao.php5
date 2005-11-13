<?php

/**
 * @author nowel
 */
class S2Dao_ArrayList {

	private $element = null;
	
	public function __construct( $object = null ){
		if( $object == null ){
			$this->element = new ArrayObject();
		} else {
			$this->element = new ArrayObject($object);
		}
	}
	
	public function size(){
        return $this->element->count();
	}
	
	public function isEmpty(){
		return empty($this->element);
    }

    public function contains($object){
        return in_array($object, $this->element->getArrayCopy(), true);
    }
	
	public function get($index){
        return $this->element->offsetGet($index);
    }
	
	public function set($index, $element){
        $this->element->offsetSet($index, $element);
    }
	
	public function add($index, $element = null){
        if( $element == null ){
            $this->element->append($index);
        } else {
            $this->element->offsetSet($index, $element);
        }
    }

    public function addAll(S2Dao_ArrayList $list){
        foreach( $list->toArray() as $value ){
            $this->element->append($value);
        }
    }
	
	public function remove($index){
        $this->element->offsetUnset($index);
    }
	
	public function iterator(){
		return $this->element->getIterator();
    }
    
	public function toArray(){
        return $this->element->getArrayCopy();
	}
}

?>
