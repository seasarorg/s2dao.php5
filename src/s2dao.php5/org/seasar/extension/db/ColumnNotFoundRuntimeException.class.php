<?php

/**
 * @author Yusuke Hata
 */
final class ColumnNotFoundRuntimeException extends S2RuntimeException {

	private $tableName_ = "";
	private $columnName_ = "";
	
	/**
	 * @param componentKey
	 */
	public function __construct($tableName, $columnName) {
		parent::__construct("ESSR0068", array( $tableName, $columnName ));
		$this->tableName_ = $tableName;
		$this->columnName_ = $columnName;
	}
	
	public function getTableName() {
		return $this->tableName_;
	}
	
	public function getColumnName() {
		return $this->columnName_;
	}
}
?>
