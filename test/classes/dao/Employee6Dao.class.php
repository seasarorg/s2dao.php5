<?php
interface Employee6Dao {

	const BEAN = "Employee2";
	
	const getEmployeesArray_QUERY = "/*IF dto.orderByString != null*/order by /*dto.orderByString*/ENAME /*END*/";
	public function getEmployeesArray(EmployeeSearchCondition $dto);
}
?>