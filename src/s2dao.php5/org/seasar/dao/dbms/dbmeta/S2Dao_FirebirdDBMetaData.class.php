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
class S2Dao_FirebirdDBMetaData implements S2Dao_DBMetaData {
    
    private $pdo;
    private $dbms;
    private $tableInfoSql = null;
    
    public function __construct(PDO $pdo, S2Dao_Dbms $dbms){
        $this->pdo = $pdo;
        $this->dbms = $dbms;
        $this->tableInfoSql = $dbms->getTableInfoSql();
    }
    
    public function getTableInfo($table){
        $retVal = array();
        $sql = str_replace(S2Dao_Dbms::BIND_TABLE, $table, $this->tableInfoSql);
        $stmt = $this->pdo->query($sql);
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
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