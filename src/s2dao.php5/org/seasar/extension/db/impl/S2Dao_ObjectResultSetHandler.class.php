<?php

/**
 * @author nowel
 */
class S2Dao_ObjectResultSetHandler implements S2Dao_ResultSetHandler {

	public function S2Dao_ObjectResultSetHandler($beanClass = null) {
	}

	public function handle($rs) {
		return $rs->fetchAll(PDO::FETCH_OBJ);
	}
}
