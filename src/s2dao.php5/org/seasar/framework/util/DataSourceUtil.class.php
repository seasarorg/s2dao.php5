<?php

/**
 * @author Yusuke Hata
 */
final class DataSourceUtil {

	private function DataSourceUtil() {
	}

	public static function getConnection(DataSource $dataSource) {
		try {
			return $dataSource->getConnection();
		} catch (Exception $ex) {
			//throw new SQLRuntimeException($ex);
			throw $ex;
		}
	}
}
?>
