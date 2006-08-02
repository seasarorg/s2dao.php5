<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
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
class S2Dao_SybaseDbMetaData extends S2Dao_StandardDbMetaData {
    
    const reg_pkey_match = '/PRIMARY KEY.+\((.+)\)/';
    
    public function __construct(PDO $pdo, S2Dao_Dbms $dbms){
        parent::__construct($pdo, $dbms);
    }
    
    public function getTableInfo($table){
        $stmt = $this->pdo->prepare($this->dbms->getTableInfoSql());
        $stmt->bindValue(S2Dao_Dbms::BIND_TABLE, $table);
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = null;
        $stmt = $this->pdo->prepare($this->dbms->getPrimaryKeySql());
        $stmt->bindValue(S2Dao_Dbms::BIND_TABLE, $table);
        $stmt->execute();
        $pkeys = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $columnMeta = array();
        foreach($columns as $column){
            $columnMeta[] = array(
                        'name' => $column['column_name'],
                        'native_type' => array(
                                            $column['type_name'],
                                            $column['sql_data_type']
                                        ),
                        'flags' => $this->getFlags($pkeys, $column),
                        'len' => $column['length'],
                        'precision' => $column['precision'],
                        'pdo_type' => null,
                    );
        }
        return $columnMeta;
    }
    
    public function getFlags(array $pkeys, array $column){
        $c = count($pkeys);
        for($i = 0; $i < $c; $i++){
            if(preg_match(self::reg_pkey_match, $pkeys[$i]['definition'], $m)){
                if(strcasecmp(trim($m[1]), $column['column_name']) == 0){
                    return (array)self::PRIMARY_KEY;
                }
            }
        }
        return null;
    }
}

?>