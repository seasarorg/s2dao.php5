<?php

/**
 * @author nowel
 */
final class ConnectionUtil {

	private function ConnectionUtil() {
	}

	public static function close($connection) {
		if ($connection == null) {
			return;
		}
		try {
			$connection->close();
		} catch (SQLException $ex) {
			throw new SQLRuntimeException($ex);
		}
	}

	public static function prepareStatement($connection, $sql) {
		try {
			return $connection->prepare($sql);
		} catch (SQLException $ex) {
			throw new SQLRuntimeException($ex);
		}
	}

    /*
	public static CallableStatement prepareCall(
			Connection connection,
			String sql) {

			try {
				return connection.prepareCall(sql);
			} catch (SQLException ex) {
				throw new SQLRuntimeException(ex);
			}
		}
    */

	public static function getMetaData($connection) {
		try {
			return $connection;
		} catch (SQLException $ex) {
			throw new SQLRuntimeException($ex);
		}
	}
}
?>
