<?php

/**
 * @author nowel
 */
final class S2Dao_DatabaseMetaDataUtil {

    const BIND_TABLE = ":TABLE";
    const PRIMARY_KEY = "primary_key";
    const REL_KEY = "foreign_key";

    private static $TABLES = array(
                    "mysql" => "SHOW TABLES",
                    "sqlite" => "SELECT name FROM sqlite_master WHERE type='table'
                                 UNION ALL SELECT name FROM sqlite_temp_master
                                 WHERE type='table' ORDER BY name",
                    "pgsql" => "SELECT c.relname AS 'Name'
                                FROM pg_class c, pg_user u
                                WHERE c.relowner = u.usesysid
                                    AND c.relkind = 'r'
                                    AND NOT EXISTS
                                    (SELECT 1 FROM pg_views
                                    WHERE viewname = c.relname)
                                    AND c.relname !~ '^(pg_|sql_)
                                UNION
                                SELECT c.relname AS 'Name'
                                FROM pg_class c
                                WHERE c.relkind = 'r'
                                    AND NOT EXISTS
                                    (SELECT 1 FROM pg_views
                                    WHERE viewname = c.relname)
                                    AND NOT EXISTS
                                    (SELECT 1 FROM pg_user
                                    WHERE usesysid = c.relowner)
                                    AND c.relname !~ '^pg_'",
                    "oracle" => "SELECT table_name FROM user_tables",
                    "db2" => null,
                    "odbc" => null,
                );

    private static $INFO = array(
                    "mysql" => "SELECT * FROM :TABLE LIMIT 0",
                    "sqlite" => "PRAGMA table_info(:TABLE)",
                    "pgsql" => "SELECT * FROM :TABLE LIMIT 0",
                    "oracle" => null,
                    "db2" => null,
                    "odbc" => null,
                );

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
                if(in_array(self::PRIMARY_KEY, $col["flags"])){
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
            foreach( $rs as $col ){
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
            throw new Exception(__FILE__.":".__METHOD__);
            return $dbMetaData->getDatabaseProductName();
        } catch (S2Container_SQLException $ex) {
            throw new S2Container_SQLRuntimeException($ex);
        }
    }

    private function getTables(PDO $db){
        $meta = self::getMetaName($db);
        switch($meta){
            case "mysql":
            case "sqlite":
            case "pgsql":
                $stmt = $db->query(self::$TABLES[$meta]);
                return $stmt->fetchAll(PDO::FETCH_COLUMN);
            default:
            case "oracle":
            case "db2":
            case "odbc":
                throw new Exception("no db case '${meta}'");
        }
    }

    private function getTableInfo(PDO $db, $table, $schema){
        $retVal = array();
        $meta = self::getMetaName($db);
        switch($meta){
            case "mysql":
            case "sqlite":
            case "pgsql":
                $sql = str_replace(self::BIND_TABLE, $table, self::$INFO[$meta]);
                $stmt = $db->query($sql);
                for($i = 0; $i < $stmt->columnCount(); $i++){
                    $retVal[] = $stmt->getColumnMeta($i);
                }
                return $retVal;
            default:
            case "oracle":
            case "db2":
            case "odbc":
                throw new Exception("no db case '${meta}'");
        }
    }

    private static function getMetaName(PDO $pdo){
        return $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }
}
?>
