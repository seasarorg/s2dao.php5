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
// $Id$
//
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
        $function = new ReflectionFunction($procedureInfo->getName());
        $params = $function->getParameters();
        foreach($params as $param){
            $type = new S2Dao_ProcedureType();
            $type->setName($param->getName());
            $type->setType(null);
            if($param->isPassedByReference()){
                $type->setInout(S2Dao_ProcedureMetaData::INOUTTYPE);
            } else {
                $type->setInout(S2Dao_ProcedureMetaData::INTYPE);
            }
            $outType[] = $type;
        }
        return $outType; 
    }
    
    public function getProcedureColumnsInOut(S2Dao_ProcedureInfo $procedureInfo){
        return $this->getProcedureColumnsOut($procedureInfo);
    }
    
    public function getProcedureColumnReturn(S2Dao_ProcedureInfo $procedureInfo){
        return null;
    }
}

?>