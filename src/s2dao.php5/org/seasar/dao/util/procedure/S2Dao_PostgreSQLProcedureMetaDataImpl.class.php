<?php

/**
 * @author nowel
 */
class S2Dao_PostgreSQLProcedureMetaDataImpl implements S2Dao_ProcedureMetaData {
    
    private $connection;
    private $dbms;
    
    public function __construct(PDO $connection, S2Dao_Dbms $dbms){
        $this->connection = $connection;
        $this->dbms = $dbms;
    }
    
    public function getProcedures($catalog = null, $scheme = null, $procedureName){
        $sql = $this->dbms->getProcedureNamesSql();
        $stmt = $this->connection->prepare($sql);
        if($scheme != null){
            $stmt->bindValue(S2Dao_Dbms::BIND_SCHEME, $scheme);
        } else {
            $stmt->bindValue(S2Dao_Dbms::BIND_SCHEME, '%');
        }
        $stmt->bindValue(S2Dao_Dbms::BIND_NAME, $procedureName);
        $stmt->execute();
        
        $procedures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ret = array();
        foreach($procedures as $procedure){
            $info = new S2Dao_ProcedureInfo();
            $info->setScheme($procedure['scheme']);
            $info->setName($procedure['proname']);
            $info->setType(self::STORED_FUNCTION);
            $ret[] = $info;
        }
        
        return $ret;
    }
    
    private function analyzeProcedureParams(S2Dao_ProcedureInfo $procedureInfo){
        if($this->analyzed){
            return $this->procedureParam;
        }
        
        $sql = $this->dbms->getProcedureInfoSql();
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(S2Dao_Dbms::BIND_SCHEME, $procedureInfo->getScheme());
        $stmt->bindValue(S2Dao_Dbms::BIND_NAME, $procedureInfo->getName());
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->procedureParam = $result;
        $this->analyzed = true;
    }
    
    public function getProcedureColumnsIn(S2Dao_ProcedureInfo $procedureInfo){
        $this->analyzeProcedureParams($procedureInfo);
        
        $inType = array();
        $args = explode(',', $this->procedureParam['argstypes']);
        $names = explode(',', str_replace(array('{', '}'), '', $this->procedureParam['argsnames']));

        $params = array_combine($names, $args);
        foreach($params as $name => $arg){
            $type = new S2Dao_ProcedureType();
            $type->setName(trim($name));
            $type->setType(trim($arg));
            $type->setInout(S2Dao_ProcedureMetaData::INTYPE);
            $inType[] = $type;
        }
        
        return $inType;
    }
    
    public function getProcedureColumnsOut(S2Dao_ProcedureInfo $procedureInfo){
        $this->analyzeProcedureParams($procedureInfo);
        
        $outType = array();
        return $outType;
    }
    
    public function getProcedureColumnsInOut(S2Dao_ProcedureInfo $procedureInfo){
        $this->analyzeProcedureParams($procedureInfo);

        $inoutType = array();
        return $inoutType;
    }
    
    public function getProcedureColumnReturn(S2Dao_ProcedureInfo $procedureInfo){
    }
}

?>