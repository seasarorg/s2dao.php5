<?php

/**
 * @author nowel
 */
class PostgreSQL extends Standard {

	public function getSuffix() {
		return "_postgre";
	}
	
	public function getSequenceNextValString($sequenceName) {
		return "select nextval ('" + $sequenceName +"')";
	}
}
?>
