<?php

/**
 * @author nowel
 */
final class S2Dao_DaoAnnotation {
    
    public $BEAN = null;
    public $ARGS = null;
    public $SQL = null;
    public $NO_PERSISTENT_PROPS = null;
    public $PERSISTENT_PROPS = null;
    private $value = null;
    
    public function __set($name, $value){
        $this->value[$name] = $value;
    }
    
    public function getValues(){
        return $this->value;
    }
    
}

?>