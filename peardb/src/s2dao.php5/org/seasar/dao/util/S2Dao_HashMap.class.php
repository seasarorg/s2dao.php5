<?php

/**
 * @author nowel
 */
class S2Dao_HashMap {
	
	private $element = array();
	
	public function size(){
		return count($this->element);
	}
	
	public function isEmpty(){
		return empty($this->element);
	}
	
	public function get($object){
        if( isset($this->element[$object]) ){
            return $this->element[$object];
        } else {
            if( is_integer($object) ){
                $arrays = array_values($this->element);
                return $arrays[$object];
            } else {
                return null;
            }
        }
	}
	
	public function put($key, $value){
		$this->element[$key] = $value;
	}
	
	public function remove($object){
	    unset($this->element[$object]);
	}
	
	public function containsKey($key){
		return isset($this->element[$key]);
	}

    public function toArray(){
        return $this->element;
    }
}
?>
