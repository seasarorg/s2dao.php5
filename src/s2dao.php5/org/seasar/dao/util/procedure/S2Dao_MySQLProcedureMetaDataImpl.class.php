<?php

/**
 * @author nowel
 */
class S2Dao_MySQLProcedureMetaDataImpl implements S2Dao_ProcedureMetaData {
    
    private $connection;
    private $dbms;
    private $procedureParam;
    private static $analyzed = false;
    
    public function __construct(PDO $connection, S2Dao_Dbms $dbms){
        $this->connection = $connection;
        $this->dbms = $dbms;
    }
    
    public function getProcedures($catalog = null, $scheme = null, $procedureName){
        $sql = $this->dbms->getProcedureNamesSql();
        $stmt = $this->connection->prepare($sql);
        if($catalog != null){
            $stmt->bindValue(S2Dao_Dbms::BIND_DB, $catalog);
        } else {
            $stmt->bindValue(S2Dao_Dbms::BIND_DB, '%');
        }
        $stmt->bindValue(S2Dao_Dbms::BIND_NAME, $procedureName);
        $stmt->execute();
        
        $procedures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ret = array();
        foreach($procedures as $procedure){
            $info = array(
                        'Catalog' => $procedure['db'],
                        'Scheme' => '',
                        'Name' => $procedure['name'],
                    );
            if(strcasecmp($procedure['type'],'PROCEDURE') == 0){
                $info['Type'] = self::STORED_PROCEDURE;
            } else {
                $info['Type'] = self::STORED_FUNCTION;
            }
            $ret[] = $info;
        }
        
        return $ret;
    }

    private function analyzeProcedureParams(array $procedureInfo){
        /*
        if(!array_key_exists(array('Catalog', 'Scheme', 'Name'), $procedureInfo)){
            throw new Exception();
        }
        */
        
        if(self::$analyzed){
            return $this->procedureParam;
        }
        
        $catalog = $procedureInfo['Catalog'];
        $procedureName = $procedureInfo['Name'];
        
        $sql = $this->dbms->getProcedureInfoSql();
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(S2Dao_Dbms::BIND_DB, $catalog);
        $stmt->bindValue(S2Dao_Dbms::BIND_NAME, $procedureName);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->procedureParam = $result['param_list'];
    }
    
    public function getProcedureColumnsIn(array $procedureInfo){
        $this->analyzeProcedureParams($procedureInfo);
        
        $inType = array();
        preg_match_all('/(IN\s+?(\S+)\s+?(\S+),?)/i', $this->procedureParam, $match, PREG_SET_ORDER);
        foreach($match as $m){
            $in = explode(',', $m[3]);
            $inType[] = array(
                            'name' => $m[2],
                            'type' => trim($in[0])
                        );
        }
        
        return $inType;
    }
    public function getProcedureColumnsOut(array $procedureInfo){
        $this->analyzeProcedureParams($procedureInfo);
        
        $outType = array();
        preg_match_all('/(OUT\s+?(\S+)\s+?(\S+),?)/i', $this->procedureParam, $match, PREG_SET_ORDER);
        foreach($match as $m){
            $out = explode(',', $m[3]);
            $outType[] = array(
                            'name' => $m[2],
                            'type' => trim($out[0])
                        );
        }
        
        return $outType;
    }
    public function getProcedureColumnsInOut(array $procedureInfo){
        $this->analyzeProcedureParams($procedureInfo);
        
        $inoutType = array();
        preg_match_all('/(^(IN|OUT)?(\S+)\s+?(\S+),?)/i', $this->procedureParam, $match, PREG_SET_ORDER);
        foreach($match as $m){
            if(!preg_match('/IN|OUT/i', $m[3])){
                $inoutType[] = array(
                                'name' => null,
                                'type' => null,
                            );
            }
        }
        
        return $inoutType;
    }
}

?>