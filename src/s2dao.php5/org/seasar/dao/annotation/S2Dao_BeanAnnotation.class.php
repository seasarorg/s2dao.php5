<?php

final class S2Dao_BeanAnnotation {
    
    public $TABLE = null;
    private $value = null;
    
    public function __set($name, $value){
        $this->value[$name] = $value;
    }
    
    public function getValues(){
        return $this->value;
    }

}

?>