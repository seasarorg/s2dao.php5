<?php

/**
 * @author nowel
 */
final class S2Dao_ArrayUtil {
    
    private function __construct(){
    }
    
    public static function spacetrim(array $elem){
        $ret = array();
        foreach($elem as $key => $value){
            $ret[$key] = trim($value);
        }
        return $ret;
    }
}

?>