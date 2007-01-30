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
// $Id: $
//
/**
 * @author nowel
 */
class S2Dao_FirebirdDbMetaData extends S2Dao_StandardDbMetaData {
    
    public function __construct(PDO $pdo, S2Dao_Dbms $dbms){
        parent::__construct($pdo, $dbms);
    }
    
    public function getTableInfo($table){
        $columns = $this->getColumns($table);
        $sql = str_replace(S2Dao_Dbms::BIND_TABLE, $table, $this->dbms->getPrimaryKeySql());
        $stmt = $this->pdo->query($sql);
        $pkeys = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $c = count($columns);
        for($i = 0; $i < $c; $i++){
            $column =& $columns[$i];
            $columnName = $column['name'];
            foreach($pkeys as $pk){
                $pk = array_change_key_case($pk, CASE_LOWER);
                $pkName = trim($pk['name']);
                if(empty($pkName)){
                    continue;
                }
                if(strcasecmp($columnName, $pkName) == 0){
                    $column['flags'] = (array)self::PRIMARY_KEY;
                }
            }
        }
        return $columns;
    }
    
    public function getColumns($table){
        $retVal = array();
        $sql = str_replace(S2Dao_Dbms::BIND_TABLE, $table, $this->dbms->getTableInfoSql());
        $stmt = $this->pdo->query($sql);
        $columns = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;
        foreach($columns as $key => $column){
            $retVal[] = array(
                            'name' => $key,
                            'native_type' => array(),
                            'flags' => null,
                            'len' => -1,
                            'precision' => 0,
                            'pdo_type' => null,
                        );
        }
        return $retVal;
    }
}

?>