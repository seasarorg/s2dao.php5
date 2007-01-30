<?php

interface Employee3Dao {
    
    const BEAN = "Employee3";

	public function getEmployeesList(Employee3 $dto);
    const getEmployees2List_QUERY = "ORDER BY empno";
	public function getEmployees2List(Employee3 $dto);
}
?>