<?php
interface Employee7Dao {

	const BEAN = "Employee2";
	
	const getCount_sqlite_SQL = "SELECT COUNT(*) FROM emp;";
	public function getCount();
	
	const deleteEmployee_SQL = "DELETE FROM emp WHERE empno=?;";
	public function deleteEmployee($empno);
}

?>