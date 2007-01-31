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
 * @package org.seasar.s2dao.dbms.dbmeta
 */
class S2Dao_OracleDbMetaData extends S2Dao_StandardDbMetaData {
    
    private $tableInfoSql = null;
    
    public function __construct(PDO $pdo, S2Dao_Dbms $dbms){
        parent::__construct($pdo, $dbms);
    }
    
    public function getTableInfo($table){
        $retVal = array();
        $sql = str_replace(S2Dao_Dbms::BIND_TABLE, '\'' . $table . '\'', $this->dbms->tableInfoSql);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $colsql = $this->dbms->getPrimaryKeySql();
        $colsql = str_replace(S2Dao_Dbms::BIND_TABLE, '\'' . $table . '\'', $colsql);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row){
            $sql = str_replace(S2Dao_Dbms::BIND_COLUMN, '\'' . $row['COLUMN_NAME'] . '\'', $colsql);
            $col = $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

            $flg = null;
            if('P' == $col['CONSTRAINT_TYPE']){
                $flg = (array)self::PRIMARY_KEY;
            }

            $retVal[] = array(
                            'name' => $row['COLUMN_NAME'],
                            'native_type' => $row['DATA_TYPE'],
                            'flags' => $flg,
                            'len' => $row['CHAR_COL_DECL_LENGTH'],
                            'precision' => $row['DATA_PRECISION'],
                            'pdo_type' => null,
                        );
        }
        return $retVal;
    }
    
}

?>