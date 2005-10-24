<?php

/**
 * @author Yusuke Hata
 */
final class DatabaseMetaDataUtil {

	private function DatabaseMetaDataUtil() {
	}

    /*
	public static String[] getPrimaryKeys(DatabaseMetaData dbMetaData,
			String tableName) {

		Set set = getPrimaryKeySet(dbMetaData, tableName);
		return (String[]) set.toArray(new String[set.size()]);
	}
    */

	public static function getPrimaryKeySet($dbMetaData, $tableName) {
		$schema = null;
		$index = strpos(".", $tableName);
		if ($index >= 0 && $index !== false) {
			$schema = substr($tableName, 0, $index);
			$tableName = substr($tableName, $index + 1);
		}
		$convertedTableName = self::convertIdentifier($dbMetaData, $tableName); 
		//$set = new CaseInsensitiveSet();
        $set = new ArrayList();
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
            $rs = $dbMetaData->tableInfo($tableName, $schema);
            foreach( $rs as $col ){
                if(preg_match("/primary_key/", $col["flags"], $match)){
                    $set->add($col["name"]);
                }
            }
		} catch (Exception $ex) {
			throw new SQLRuntimeException($ex);
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
        $set = new ArrayList();
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
            $rs = $dbMetaData->tableInfo($tableName, $schema);
            foreach( $rs as $col ){
                $set->add($col["name"]);
            }
			//$rs = $dbMetaData->getColumns(null, $schema, $tableName, null);
			//while ($rs->next()) {
			//	$set->add($rs->getString(4));
			//}
			//$rs->close();
		} catch (Exception $ex) {
			throw new SQLRuntimeException($ex);
		}
	}

	public static function convertIdentifier($dbMetaData, $identifier) {
        $tables = $dbMetaData->getTables();
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
		} catch (SQLException ex) {
			throw new SQLRuntimeException(ex);
		}
	}

	public static boolean storesUpperCaseIdentifiers(DatabaseMetaData dbMetaData) {
		try {
			return dbMetaData.storesUpperCaseIdentifiers();
		} catch (SQLException ex) {
			throw new SQLRuntimeException(ex);
		}
	}
    */

	public static function getDatabaseProductName($dbMetaData) {
        try {
			return $dbMetaData->getDatabaseProductName();
		} catch (SQLException $ex) {
			throw new SQLRuntimeException($ex);
		}
	}
}
?>
