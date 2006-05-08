<?php

/**
 * @author nowel
 */
class S2Dao_ProcedureMetaDataFactory {
    
    public static function createProcedureMetaData(PDO $connection){
        $dbms = S2Dao_DatabaseMetaDataUtil::getDbms($connection);
        
        if($dbms instanceof S2Dao_MySQL){
            return new S2Dao_MySQLProcedureMetaDataImpl($connection, $dbms);
        } else if($dbms instanceof S2Dao_PostgreSQL){
            return new S2Dao_PostgreSQLProcedureMetaDataImpl($connection, $dbms);
        } else if($dbms instanceof S2Dao_SQLite){
            return new S2Dao_SQLiteProcedureMetaDataImpl($connection, $dbms);
        } else {
            throw new Exception('not supported ' . get_class($dbms));
        }
    }
    
}

?>