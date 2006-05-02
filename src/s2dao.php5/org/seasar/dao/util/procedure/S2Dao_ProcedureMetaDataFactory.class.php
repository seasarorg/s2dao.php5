<?php

/**
 * @author nowel
 */
class S2Dao_ProcedureMetaDataFactory {
    
    public static function createProcedureMetaData(PDO $connection){
        $dbms = S2Dao_DatabaseMetaDataUtil::getDbms($connection);
        
        if($dbms instanceof S2Dao_MySQL){
            return new S2Dao_MySQLProcedureMetaDataImpl($connection, $dbms);
        } else {
            throw new Exception('not supported ' . $dbms->getSuffix());
        }
    }
    
}

?>