<?php

/**
 * @author nowel
 */
final class DataSourceUtil {

	private function DataSourceUtil() {
	}

	public static function getConnection(DataSource $dataSource) {
		try {
			return $dataSource->getConnection();
		} catch (Exception $ex) {
			throw $ex;
		}
	}
}
?>
