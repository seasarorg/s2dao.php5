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
final class S2Dao_DatabaseMetaDataUtil {

    private function __construct() {
    }
    
    public static function getDbms(PDO $db){
        $dbms = S2Dao_DbmsManager::getDbms($db);
        if($dbms === null){
            throw new Exception('not such dbms case');
        }
        return $dbms;
    }
    
    public static function getDbMeta(PDO $pdo){
        return S2Dao_DBMetaDataFactory::create($pdo, self::getDbms($pdo));
    }

    public static function getPrimaryKeys(PDO $dbMetaData, $tableName) {
        return self::getPrimaryKeySet($dbMetaData, $tableName)->toArray();
    }

    public static function getPrimaryKeySet(PDO $dbMetaData, $tableName) {
        $schema = null;
        $index = strpos('.', $tableName);
        if ($index >= 0 && $index !== false) {
            $schema = substr($tableName, 0, $index);
            $tableName = substr($tableName, $index + 1);
        }
        $convertedTableName = self::convertIdentifier($dbMetaData, $tableName);
        $set = new S2Dao_ArrayList();
        self::addPrimaryKeys($dbMetaData,
                              self::convertIdentifier($dbMetaData, $schema),
                              $convertedTableName,
                              $set);

        if ($set->size() == 0) {
            self::addPrimaryKeys($dbMetaData, $schema, $tableName, $set);
        }
        if ($set->size() == 0 && $schema != null) {
            self::addPrimaryKeys($dbMetaData, null, $convertedTableName, $set);
            if ($set->size() == 0) {
                self::addPrimaryKeys($dbMetaData, null, $tableName, $set);
            }
        }
        return $set;
    }
    
    private static function addPrimaryKeys($dbMetaData, $schema, $tableName, $set) {
        try {
            $rs = self::getTableInfo($dbMetaData, $tableName, $schema);
            foreach($rs as $col){
                if(isset($col['flags']) &&
                        in_array(S2Dao_DBMetaData::PRIMARY_KEY, $col['flags'])){
                    $set->add($col['name']);
                }
            }
        } catch (Exception $ex) {
            throw new S2Dao_SQLRuntimeException($ex);
        }
    }

    public static function getColumns(PDO $dbMetaData, $tableName) {
        return self::getColumnSet($dbMetaData, $tableName)->toArray();
    }
    
    public static function getColumnSet(PDO $dbMetaData, $tableName) {
        $schema = null;
        $index = strpos('.', $tableName);
        if ($index >= 0 && $index !== false) {
            $schema = substr($tableName, 0, $index);
            $tableName = substr($tableName, $index + 1);
        }
        
        $convertedTableName = self::convertIdentifier($dbMetaData, $tableName);
        $set = new S2Dao_ArrayList();
        self::addColumns($dbMetaData,
                         self::convertIdentifier($dbMetaData, $schema),
                         $convertedTableName,
                         $set);

        if ($set->size() == 0) {
            self::addColumns($dbMetaData, $schema, $tableName, $set);
        }
        if ($set->size() == 0 && $schema != null) {
            self::addColumns($dbMetaData, null, $convertedTableName, $set);
            if ($set->size() == 0) {
                self::addColumns($dbMetaData, null, $tableName, $set);
            }
        }
        return $set;
    }
    
    private static function addColumns(PDO $pdo, $schema, $tableName, $set) {
        try {
            $rs = self::getTableInfo($pdo, $tableName, $schema);
            foreach($rs as $col){
                $set->add($col['name']);
            }
        } catch (Exception $ex) {
            throw new S2Dao_SQLRuntimeException($ex);
        }
    }

    public static function convertIdentifier(PDO $dbMetaData, $identifier) {
        $tables = self::getTables($dbMetaData);
        if (!in_array($identifier, $tables, true)) {
            $upper = strtoupper($identifier);
            $lower = strtolower($identifier);
            if (in_array($upper, $tables, true)) {
                return $upper;
            } else if(in_array($lower, $tables, true)){
                return $lower;
            } else {
                return $identifier;
            }
        }
        return $identifier;
    }
    
    private static function getTables(PDO $pdo){
        $stmt = $pdo->query(self::getDbms($pdo)->getTableSql());
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private static function getTableInfo(PDO $pdo, $table, $schema){
        return self::getDbMeta($pdo)->getTableInfo($table);
    }
}
?>
