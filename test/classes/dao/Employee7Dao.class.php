<?php
interface Employee7Dao {

	const BEAN = "Employee2";
	
	const getCount_sqlite_SQL = "SELECT COUNT(*) FROM emp2;";
	public function getCount();
	
	const deleteEmployee_SQL = "DELETE FROM emp2 WHERE empno = /*empno*/;";
	public function deleteEmployee($empno);
}

?>