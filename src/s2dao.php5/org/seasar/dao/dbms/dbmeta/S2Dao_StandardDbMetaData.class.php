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
class S2Dao_StandardDbMetaData implements S2Dao_DbMetaData {
    
    protected $pdo;
    protected $dbms;
    
    public function __construct(PDO $pdo, S2Dao_Dbms $dbms){
        $this->pdo = $pdo;
        $this->dbms = $dbms;
    }
    
    public function getTableInfo($table){
        $sql = str_replace(S2Dao_Dbms::BIND_TABLE, $table, $this->dbms->getTableInfoSql());
        $stmt = $this->pdo->query($sql);

        $retVal = array();
        for($i = 0; $i < $stmt->columnCount(); $i++){
            $retVal[] = $stmt->getColumnMeta($i);
        }
        return $retVal;
    }
    
    public function getProcedureInfo($procedure){
        return null;
    }
}

?>