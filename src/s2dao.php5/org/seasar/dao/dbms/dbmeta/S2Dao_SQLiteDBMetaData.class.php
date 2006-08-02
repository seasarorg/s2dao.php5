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
class S2Dao_SQLiteDBMetaData implements S2Dao_DBMetaData {
    
    private $pdo;
    private $dbms;
    
    public function __construct(PDO $pdo, S2Dao_Dbms $dbms){
        $this->pdo = $pdo;
        $this->dbms = $dbms;
    }
    
    public function getTableInfo($table){
        $columnMeta = $this->getColumnMeta($table);
        
        $sql = str_replace(S2Dao_Dbms::BIND_TABLE, $table, $this->dbms->getPrimaryKeySql());
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        foreach($columnMeta as &$value){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(is_array($row) && $row['name'] == $value['name']){
                if($row['pk'] == '1'){
                    $value['flags'] = (array)self::PRIMARY_KEY;
                }
            }
        }
        return $columnMeta;
    }
    
    private function getColumnMeta($table){
        $retVal = array();
        $sql = str_replace(S2Dao_Dbms::BIND_TABLE, $table,
                            $this->dbms->getTableInfoSql());

        try {
            $stmt = $this->pdo->query($sql);
            for($i = 0; $i < $stmt->columnCount(); $i++){
                $retVal[] = $stmt->getColumnMeta($i);
            }
        } catch(PDOException $e){
            $sql = str_replace(S2Dao_Dbms::BIND_TABLE, $table,
                                $this->dbms->getPrimaryKeySql());
            $stmt = $this->pdo->query($sql);
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $pk = false;
                if($row['pk'] == '1'){
                    $pk = true;
                }
                $retVal[] = array(
                            'name' => $row['name'],
                            'native_type' => array($row['type']),
                            'flags' => $pk === true ? (array)self::PRIMARY_KEY : array(),
                            'len' => -1,
                            'precision' => 0,
                            'pdo_type' => 2,
                        );
            }
        }
        return $retVal;
    }
}

?>