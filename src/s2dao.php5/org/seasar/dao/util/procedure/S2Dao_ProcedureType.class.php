<?php

/**
 * @author nowel
 */
class S2Dao_ProcedureType {
    
    private $name;
    private $type;
    private $inout;
    
    public function __construct($name = null, $type = null, $inout = null){
        $this->name = $name;
        $this->type = $type;
        $this->inout = $inout;
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setType($type){
        $this->type = $type;
    }
    
    public function getType(){
        return $this->type;
    }
    
    public function setInout($inout){
        $this->inout = $inout;
    }
    
    public function getInout(){
        return $this->inout;
    }
}

?>