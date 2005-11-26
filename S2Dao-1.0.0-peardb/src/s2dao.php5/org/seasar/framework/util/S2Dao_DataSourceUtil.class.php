<?php

/**
 * @author nowel
 */
final class S2Dao_DataSourceUtil {

	private function S2Dao_DataSourceUtil() {
	}

	public static function getConnection(S2Container_DataSource $dataSource) {
		try {
			return $dataSource->getConnection();
		} catch (Exception $ex) {
			throw $ex;
		}
	}
}
?>
