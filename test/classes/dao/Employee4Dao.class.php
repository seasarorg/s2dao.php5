<?php

interface Employee4Dao {

	const BEAN = "Employee4";
	
	const getEmployee_ARGS = "empno";
	public function getEmployee($empno);
}

?>