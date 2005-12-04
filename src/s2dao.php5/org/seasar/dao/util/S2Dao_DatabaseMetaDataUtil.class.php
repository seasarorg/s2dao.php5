<?php

/**
 * @author nowel
 */
final class S2Dao_DatabaseMetaDataUtil {

    const PRIMARY_KEY = "primary_key";
    const REL_KEY = "foreign_key";

    private function S2Dao_DatabaseMetaDataUtil() {
    }

    /*
    public static String[] getPrimaryKeys(DatabaseMetaData dbMetaData,
            String tableName) {

        Set set = getPrimaryKeySet(dbMetaData, tableName);
        return (String[]) set.toArray(new String[set.size()]);
    }
    */

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

    /*
    public static String[] getColumns(DatabaseMetaData dbMetaData,
            String tableName) {

        Set set = getColumnSet(dbMetaData, tableName);
        return (String[]) set.toArray(new String[set.size()]);
    }
    */
    
    public static function getColumnSet($dbMetaData, $tableName) {
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

    /*
    public static boolean supportsMixedCaseIdentifiers(
            DatabaseMetaData dbMetaData) {

        try {
            return dbMetaData.supportsMixedCaseIdentifiers();
        } catch (S2Container_SQLException ex) {
            throw new S2Container_SQLRuntimeException(ex);
        }
    }

    public static boolean storesUpperCaseIdentifiers(DatabaseMetaData dbMetaData) {
        try {
            return dbMetaData.storesUpperCaseIdentifiers();
        } catch (S2Container_SQLException ex) {
            throw new S2Container_SQLRuntimeException(ex);
        }
    }
    */

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
        $sql = str_replace(S2Dao_Dbms::BIND_TABLE, $table, $dbms->getTableInfoSql());
        $stmt = $db->query($sql);

        for($i = 0; $i < $stmt->columnCount(); $i++){
            $retVal[] = $stmt->getColumnMeta($i);
        }

        if(preg_match("/sqlite/i", get_class($dbms), $m)){
            self::sqlite_metadata($db, $dbms, $table, $retVal);
        } else if(preg_match("/pgsql/i", get_class($dbms), $m)){
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

}
?>
