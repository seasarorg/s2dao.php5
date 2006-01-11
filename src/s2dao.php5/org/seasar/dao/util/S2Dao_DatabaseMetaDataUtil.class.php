<?php

/**
 * @author nowel
 */
final class S2Dao_DatabaseMetaDataUtil {

    const PRIMARY_KEY = "primary_key";
    //const REL_KEY = "foreign_key";
    const REL_KEY = "multiple_key";

    private function S2Dao_DatabaseMetaDataUtil() {
    }

    public static function getPrimaryKeys(PDO $dbMetaData,$tableName) {

        return $this->getPrimaryKeySet($dbMetaData, $tableName)->toArray();
    }

    public static function getPrimaryKeySet(PDO $dbMetaData, $tableName) {
        $schema = null;
        $index = strpos(".", $tableName);
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
                if(isset($col["flags"]) && in_array(self::PRIMARY_KEY, $col["flags"])){
                    $set->add($col["name"]);
                }
            }
        } catch (Exception $ex) {
            throw new S2Container_SQLRuntimeException($ex);
        }
    }

    public static function getColumns(PDO $dbMetaData, $tableName) {
        return $this->getColumnSet($dbMetaData, $tableName)->toArray();
    }
    
    public static function getColumnSet(PDO $dbMetaData, $tableName) {
        $schema = null;
        $index = strpos(".", $tableName);
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
    
    private static function addColumns($dbMetaData, $schema, $tableName, $set) {
        try {
            $rs = self::getTableInfo($dbMetaData, $tableName, $schema);
            foreach($rs as $col){
                $set->add($col["name"]);
            }
        } catch (Exception $ex) {
            throw new S2Container_SQLRuntimeException($ex);
        }
    }

    public static function convertIdentifier(PDO $dbMetaData, $identifier) {
        $tables = self::getTables($dbMetaData);
        if (!in_array($identifier, $tables, true)) {
            $upper = strtoupper($identifier);
            $lower = strtolower($identifier);
            if (in_array($upper, $tables, true)) {
                return $upper;
            } else if( in_array($lower, $tables, true) ){
                return $lower;
            } else {
                return $identifier;
            }
        } else {
            return $identifier;
        }
    }

    public static function getDatabaseProductName($dbMetaData) {
        try {
            throw new Exception(__FILE__ . ":" . __METHOD__);
            return $dbMetaData->getDatabaseProductName();
        } catch (S2Container_SQLException $ex) {
            throw new S2Container_SQLRuntimeException($ex);
        }
    }

    private function getTables(PDO $db){
        $dbms = self::getDbms($db);
        $stmt = $db->query($dbms->getTableSql());
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    private function getTableInfo(PDO $db, $table, $schema){
        $dbms = self::getDbms($db);

        // {{{
        // dbms instanceof Firebird/Oracle
        if($dbms instanceof S2Dao_Firebird){
            return self::firebird_metadata($db, $dbms, $table);
        } else if($dbms instanceof S2Dao_Oracle){
            return self::oracle_metadata($db, $dbms, $table);
        }
        // else
        // }}}

        $sql = str_replace(S2Dao_Dbms::BIND_TABLE, $table, $dbms->getTableInfoSql());
        $stmt = $db->query($sql);

        $retVal = array();
        for($i = 0; $i < $stmt->columnCount(); $i++){
            $retVal[] = $stmt->getColumnMeta($i);
        }

        if($dbms instanceof S2Dao_SQLite){
            self::sqlite_metadata($db, $dbms, $table, $retVal);
        } else if($dbms instanceof S2Dao_PostgreSQL){
            self::pg_metadata($db, $dbms, $table, $retVal);
        }

        return $retVal;
    }
    
    private static function getDbms(PDO $db){
        $dbms = S2Dao_DbmsManager::getDbms($db);
        if($dbms === null){
            throw new Exception("not such dbms case");
        }
        return $dbms;
    }

    private function sqlite_metadata(PDO $db, S2Dao_Dbms $dbms, $table, array &$retVal){
        $sql = str_replace(S2Dao_Dbms::BIND_TABLE, $table, $dbms->getPrimaryKeySql());
        $stmt = $db->prepare($sql);
        $stmt->execute();
        foreach($retVal as &$value){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if( is_array($row) && $row["name"] == $value["name"] ){
                if( $row["pk"] == "1" ){
                    $value["flags"] = (array)self::PRIMARY_KEY;
                }
            }
        }
    }

    private function pg_metadata(PDO $db, S2Dao_Dbms $dbms, $table, array &$retVal){
        $stmt = $db->prepare($dbms->getPrimaryKeySql());
        $stmt->bindValue(S2Dao_Dbms::BIND_TABLE, $table);
        $stmt->execute();
        foreach($retVal as &$value){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if( is_array($row) && $row["pkey"] == $value["name"] ){
                $value["flags"] = (array)self::PRIMARY_KEY;
            }
        }
    }

    private function firebird_metadata(PDO $db, S2Dao_Dbms $dbms, $table){
        $retVal = array();
        $stmt = $db->prepare($dbms->getTableInfoSql());
        $stmt->bindValue(S2Dao_Dbms::BIND_TABLE, $table);
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_NAMED);
        foreach($columns as $key => $column){
            $retVal[] = array(
                            "name" => $key,
                            "native_type" => array(),
                            "flags" => null,
                            "len" => -1,
                            "precision" => 0,
                            "pdo_type" => null,
                        );
        }
        return $retVal;
    }

    private function oracle_metadata(PDO $db, S2Dao_Dbms $dbms, $table){
        $retVal = array();
        $sql = str_replace(S2Dao_Dbms::BIND_TABLE, '\''.$table.'\'', $dbms->getTableInfoSql());
        $stmt = $db->prepare($sql);
        $stmt->execute();

        // FIXME hangup
        $colsql = $dbms->getPrimaryKeySql();
        $colsql = str_replace(S2Dao_Dbms::BIND_TABLE, '\''.$table.'\'', $colsql);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row){
            // FIXME hangup
            $sql = str_replace(S2Dao_Dbms::BIND_COLUMN, '\''.$row["COLUMN_NAME"].'\'', $colsql);
            $stcol = $db->query($sql);
            $col = $stcol->fetch(PDO::FETCH_ASSOC);

            $flg = null;
            if("P" == $col["CONSTRAINT_TYPE"]){
                $flg = (array)self::PRIMARY_KEY;
            }

            $retVal[] = array(
                            "name" => $row["COLUMN_NAME"],
                            "native_type" => $row["DATA_TYPE"],
                            "flags" => $flg,
                            "len" => $row["CHAR_COL_DECL_LENGTH"],
                            "precision" => $row["DATA_PRECISION"],
                            "pdo_type" => null,
                        );
        }
        return $retVal;
    }
}
?>
