<?php

/**
 * @author nowel
 */
final class S2Dao_PDOType {
    
    public static function gettype($phpType = null){
        if($phpType === null){
            return PDO::PARAM_NULL;
        }
        
        switch($phpType){
            case S2Dao_PHPType::String:
                return PDO::PARAM_STR;
            case S2Dao_PHPType::Integer:
                return PDO::PARAM_INT;
            case S2Dao_PHPType::Boolean:
                return PDO::PARAM_BOOL;
            case S2Dao_PHPType::Null:
                return PDO::PARAM_NULL;
            case S2Dao_PHPType::Resource:
                return PDO::PARAM_LOB;
            default:
            case S2Dao_PHPType::Object:
            case S2Dao_PHPType::Double:
            case S2Dao_PHPType::Float:
                return PDO::PARAM_STMT||PDO::PARAM_INPUT_OUTPUT;
        }
    }

}
?>