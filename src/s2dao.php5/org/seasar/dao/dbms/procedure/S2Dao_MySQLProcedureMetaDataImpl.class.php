<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id: $
//
/**
 * @author nowel
 * @package org.seasar.s2dao.dbms.procedure
 */
class S2Dao_MySQLProcedureMetaDataImpl implements S2Dao_ProcedureMetaData {
    
    private $connection;
    private $dbms;
    private $procedureParam;
    private $analyzed = false;
    
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
            $info = new S2Dao_ProcedureInfo();
            $info->setCatalog($procedure['db']);
            $info->setName($procedure['name']);
            if(0 === strcasecmp($procedure['type'], 'PROCEDURE')){
                $info->setType(self::STORED_PROCEDURE);
            } else {
                $info->setType(self::STORED_FUNCTION);
            }
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
        $stmt->bindValue(S2Dao_Dbms::BIND_DB, $procedureInfo->getCatalog());
        $stmt->bindValue(S2Dao_Dbms::BIND_NAME, $procedureInfo->getName());
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->procedureParam = $result['param_list'];
        $this->analyzed = true;
    }
    
    public function getProcedureColumnsIn(S2Dao_ProcedureInfo $procedureInfo){
        $this->analyzeProcedureParams($procedureInfo);
        
        $inType = array();
        $params = explode(',', $this->procedureParam);
        foreach($params as $param){
            $param = trim($param);
            if(preg_match_all('/(^IN\s+?(.+)\s+?(.+))/i', $param, $match, PREG_SET_ORDER)){
                foreach($match as $m){
                    $type = new S2Dao_ProcedureType(trim($m[2]), trim($m[3]));
                    $type->setInout(S2Dao_ProcedureMetaData::INTYPE);
                    $inType[] = $type;
                }
            }
        }
        return $inType;
    }
    
    public function getProcedureColumnsOut(S2Dao_ProcedureInfo $procedureInfo){
        $this->analyzeProcedureParams($procedureInfo);
        
        $outType = array();
        $params = explode(',', $this->procedureParam);
        foreach($params as $param){
            $param = trim($param);
            if(preg_match_all('/(^OUT\s+?(.+)\s+?(.+))/i', $param, $match, PREG_SET_ORDER)){
                foreach($match as $m){
                    $type = new S2Dao_ProcedureType(trim($m[2]), trim($m[3]));
                    $type->setInout(S2Dao_ProcedureMetaData::OUTTYPE);
                    $outType[] = $type;
                }
            }
        }
        return $outType;
    }
    
    public function getProcedureColumnsInOut(S2Dao_ProcedureInfo $procedureInfo){
        $this->analyzeProcedureParams($procedureInfo);
        
        $inoutType = array();
        $params = explode(',', $this->procedureParam);
        
        foreach($params as $param){
            $param = trim($param);
            if(preg_match_all('/((^INOUT\s+)?(.+)\s+?(.+))/i', $param, $match, PREG_SET_ORDER)){
                foreach($match as $m){
                   if(preg_match('/^(IN|OUT)\s+?/i', $m[3])){
                        continue;
                    }
                    $type = new S2Dao_ProcedureType(trim($m[3]), trim($m[4]));
                    $type->setInout(S2Dao_ProcedureMetaData::INOUTTYPE);
                    $inoutType[] = $type;
                }
            }
        }
        return $inoutType;
    }
    
    public function getProcedureColumnReturn(S2Dao_ProcedureInfo $procedureInfo){
    }
}

?>