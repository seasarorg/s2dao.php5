<?php

/**
 * @author nowel
 */
class S2Dao_ProcedureInfo {
    
    private $catalog = '';
    private $scheme = '';
    private $name = '';
    private $type = '';
    
    public function __construc($catalog, $scheme, $name, $type){
        $this->catalog = $catalog;
        $this->scheme = $scheme;
        $this->name = $name;
        $this->type = $type;
    }
    
    public function setCatalog($catalog){
        $this->catalog = $catalog;
    }
    
    public function getCatalog(){
        return $this->catalog;
    }
    
    public function setScheme($scheme){
        $this->scheme = $scheme;
    }
    
    public function getScheme(){
        return $this->scheme;
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
}

?>