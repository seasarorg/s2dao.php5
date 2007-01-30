<?php

class Aaa {
    
    public function __set($name, $prop){
        $this->$name = $prop;
    }
    
    public function __get($name){
        return $this->$name;
    }
}

?>