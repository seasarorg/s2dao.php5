<?php

/**
 * @author nowel
 */
final class S2Dao_ConnectionUtil {

	private function S2Dao_ConnectionUtil() {
	}

	public static function close($connection) {
		if ($connection == null) {
			return;
		}
		try {
			$connection->close();
		} catch (S2Container_SQLException $ex) {
			throw new S2Container_SQLRuntimeException($ex);
		}
	}

	public static function prepareStatement($connection, $sql) {
		try {
			return $connection->prepare($sql);
		} catch (S2Container_SQLException $ex) {
			throw new S2Container_SQLRuntimeException($ex);
		}
	}

    /*
	public static CallableStatement prepareCall(
			Connection connection,
			String sql) {

			try {
				return connection.prepareCall(sql);
			} catch (S2Container_SQLException ex) {
				throw new S2Container_SQLRuntimeException(ex);
			}
		}
    */

	public static function getMetaData($connection) {
		try {
			return $connection;
		} catch (S2Container_SQLException $ex) {
			throw new S2Container_SQLRuntimeException($ex);
		}
	}
}
?>
