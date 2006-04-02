<?php

final class S2Dao_DaoAnnotation {
    
    public $BEAN = null;
    private $value = null;
    
    public function __set($name, $value){
        $this->value[$name] = $value;
    }
    
    public function getValues(){
        return $this->value;
    }
    
}

?>