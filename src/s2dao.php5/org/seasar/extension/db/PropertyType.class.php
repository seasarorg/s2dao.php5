<?php

/**
 * @author Yusuke Hata
 */
interface PropertyType {

	public function getPropertyDesc();

	public function getValueType();
	
	public function getPropertyName();
	
	public function getColumnName();
	
	public function setColumnName($columnName);
	
	public function isPrimaryKey();
	
	public function setPrimaryKey($primaryKey);
	
	public function isPersistent();
	
	public function setPersistent($persistent);
	
}
?>
