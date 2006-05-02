<?php

/**
 * @author nowel
 */
class S2Dao_PostgreProcedureMetaDatImpl implements S2Dao_ProcedureMetaData {
    
    private $connection;
    private $dbms;
    
    public function __construct(PDO $connection, S2Dao_Dbms $dbms){
        $this->connection = $connection;
        $this->dbms = $dbms;
    }
    
    public function getProcedures($catalog = null, $scheme = null, $procedureName){
    }

    public function getProcedureColumnsIn(array $procedureInfo){
    }
    
    public function getProcedureColumnsOut(array $procedureInfo){
    }
    
    public function getProcedureColumnsInOut(array $procedureInfo){
    }
}

?>