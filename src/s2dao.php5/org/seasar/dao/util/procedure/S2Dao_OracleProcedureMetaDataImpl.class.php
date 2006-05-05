<?php

/**
 * @author nowel
 */
class S2Dao_OracleProcedureMetaDatImpl implements S2Dao_ProcedureMetaData {
    
    private $connection;
    private $dbms;
    
    public function __construct(PDO $connection, S2Dao_Dbms $dbms){
        $this->connection = $connection;
        $this->dbms = $dbms;
    }
    
    public function getProcedures($catalog = null, $scheme = null, $procedureName){
    }

    public function getProcedureColumnsIn(S2Dao_ProcedureInfo $procedureInfo){
    }
    
    public function getProcedureColumnsOut(S2Dao_ProcedureInfo $procedureInfo){
    }
    
    public function getProcedureColumnsInOut(S2Dao_ProcedureInfo $procedureInfo){
    }
}

?>