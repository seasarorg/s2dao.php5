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
 * @package org.seasar.s2dao.util
 */
final class S2Dao_DatabaseMetaDataUtil {

    private function __construct() {
    }
    
    public static function getDbMeta(PDO $pdo){
        return S2Dao_DBMetaDataFactory::create($pdo, S2DaoDbmsManager::getDbms($pdo));
    }

    public static function getPrimaryKeys(PDO $dbMetaData, $tableName) {
        return self::getPrimaryKeySet($dbMetaData, $tableName)->toArray();
    }

    public static function getPrimaryKeySet(PDO $dbMetaData, $tableName) {
        $schema = null;
        $index = strpos('.', $tableName);
        if (0 <= $index && $index !== false) {
            $schema = substr($tableName, 0, $index);
            $tableName = substr($tableName, $index + 1);
        }
        $convertedTableName = self::convertIdentifier($dbMetaData, $tableName);
        $set = new S2Dao_CaseInsensitiveSet();
        self::addPrimaryKeys($dbMetaData,
                              self::convertIdentifier($dbMetaData, $schema),
                              $convertedTableName,
                              $set);

        $size = $set->size();
        if ($size === 0) {
            self::addPrimaryKeys($dbMetaData, $schema, $tableName, $set);
        }
        if ($size === 0 && $schema != null) {
            self::addPrimaryKeys($dbMetaData, null, $convertedTableName, $set);
            if ($size === 0) {
                self::addPrimaryKeys($dbMetaData, null, $tableName, $set);
            }
        }
        return $set;
    }
    
    /**
     * @throws S2Dao_SQLRuntimeException
     */
    private static function addPrimaryKeys($dbMetaData, $schema, $tableName, $set) {
        try {
            $rs = self::getTableInfo($dbMetaData, $tableName, $schema);
            foreach($rs as $col){
                $col = array_change_key_case($col, CASE_LOWER);
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
    
    private static function getColumnMap(PDO $dbMetaData, $tableName) {
        $map = new S2Dao_CaseInsensitiveMap();
        $set = self::getColumnSet($dbMetaData, $tableName);
        $c = $set->size();
        for($i = 0; $i < $c; ++$i){
            $map->put($set->get($i), null);
        }
        return $map;
    }
    
    public static function getColumnSet(PDO $dbMetaData, $tableName) {
        $schema = null;
        $index = strpos('.', $tableName);
        if ($index >= 0 && $index !== false) {
            $schema = substr($tableName, 0, $index);
            $tableName = substr($tableName, $index + 1);
        }
        
        $convertedTableName = self::convertIdentifier($dbMetaData, $tableName);
        $set = new S2Dao_CaseInsensitiveSet();
        self::addColumns($dbMetaData,
                         self::convertIdentifier($dbMetaData, $schema),
                         $convertedTableName,
                         $set);

        $size = $set->size();
        if ($size === 0) {
            self::addColumns($dbMetaData, $schema, $tableName, $set);
        }
        if ($size === 0 && $schema != null) {
            self::addColumns($dbMetaData, null, $convertedTableName, $set);
            if ($size === 0) {
                self::addColumns($dbMetaData, null, $tableName, $set);
            }
        }
        return $set;
    }
    
    /**
     * @throws S2Dao_SQLRuntimeException
     */
    private static function addColumns(PDO $pdo, $schema, $tableName, $set) {
        try {
            $rs = self::getTableInfo($pdo, $tableName, $schema);
            foreach($rs as $col){
                $col = array_change_key_case($col, CASE_LOWER);
                $set->add($col['name']);
            }
        } catch (Exception $ex) {
            throw new S2Dao_SQLRuntimeException($ex);
        }
    }

    public static function convertIdentifier(PDO $dbMetaData, $identifier) {
        $tables = S2Dao_ArrayUtil::spacetrim(self::getTables($dbMetaData));
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
        $stmt = $pdo->query(S2DaoDbmsManager::getDbms($pdo)->getTableSql());
        $tables = array();
        while($row = $stmt->fetch(PDO::FETCH_COLUMN)){
            $tables[] = $row;
        }
        return $tables;
    }

    private static function getTableInfo(PDO $pdo, $table, $schema){
        return self::getDbMeta($pdo)->getTableInfo($table);
    }
}
?>
