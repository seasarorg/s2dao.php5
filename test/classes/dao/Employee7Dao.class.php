<?php
interface Employee7Dao {
	const BEAN = "Employee7";
	
	const getCount_sqlite_SQL = "SELECT COUNT(*) FROM EMP3;";
    const getCount_SQL = "SELECT COUNT(*) FROM EMP3";
	public function getCount();
	
	const deleteEmployee_SQL = "DELETE FROM EMP3 WHERE EMPNO = /*empno*/;";
	public function deleteEmployee($empno);
}

?>