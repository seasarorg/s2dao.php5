<?php

interface Employee5Dao {

	const BEAN = "Employee5";
	
	const getEmployee_ARGS = "empno";
	public function getEmployee($empno);
}

?>