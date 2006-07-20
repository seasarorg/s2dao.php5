<?php
interface Employee7Dao {

	const BEAN = "Employee7";
	
	const getCount_sqlite_SQL = "SELECT COUNT(*) FROM emp3;";
	public function getCount();
	
	const deleteEmployee_SQL = "DELETE FROM emp3 WHERE empno = /*empno*/;";
	public function deleteEmployee($empno);
}

?>