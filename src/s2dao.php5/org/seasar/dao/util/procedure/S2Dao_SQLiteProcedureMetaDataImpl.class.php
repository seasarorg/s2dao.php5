<?php

/**
 * @author nowel
 */
class S2Dao_SQLiteProcedureMetaDataImpl implements S2Dao_ProcedureMetaData {
    
    private $connection;
    private $dbms;
    
    public function __construct(PDO $connection, S2Dao_Dbms $dbms){
        $this->connection = $connection;
        $this->dbms = $dbms;
    }
    
    public function getProcedures($catalog = null, $scheme = null, $procedureName){
        if(!function_exists($procedureName)){
            return null;
        }
        
        $function = new ReflectionFunction($procedureName);
        $params = $function->getParameters();
        $this->connection->sqliteCreateFunction($procedureName, $procedureName, count($params));
        
        $info = new S2Dao_ProcedureInfo();
        $info->setName($procedureName);
        $info->setType(self::STORED_FUNCTION);
        return array($info);
    }

    public function getProcedureColumnsIn(S2Dao_ProcedureInfo $procedureInfo){
        $inType = array();
        $function = new ReflectionFunction($procedureInfo->getName());
        $params = $function->getParameters();
        
        foreach($params as $param){
            $type = new S2Dao_ProcedureType();
            $type->setName($param->getName());
            $type->setType(null);
            $type->setInout(S2Dao_ProcedureMetaData::INTYPE);
            $inType[] = $type;
        }
        
        return $inType;
    }
    
    public function getProcedureColumnsOut(S2Dao_ProcedureInfo $procedureInfo){
        $outType = array();
        return $outType; 
    }
    
    public function getProcedureColumnsInOut(S2Dao_ProcedureInfo $procedureInfo){
        $inoutType = array();
        return $inoutType;
    }
    
    public function getProcedureColumnReturn(S2Dao_ProcedureInfo $procedureInfo){
        return null;
    }
}

?>