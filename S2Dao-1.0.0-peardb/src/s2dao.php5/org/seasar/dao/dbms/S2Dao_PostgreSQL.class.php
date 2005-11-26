<?php

/**
 * @author nowel
 */
class S2Dao_PostgreSQL extends S2Dao_Standard {

	public function getSuffix() {
		return "_postgre";
	}
	
	public function getSequenceNextValString($sequenceName) {
		return "select nextval ('" + $sequenceName +"')";
	}
}
?>
