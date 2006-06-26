<?php

interface EmployeeAutoDao {

    const BEAN = "Employee2";
    
    const getEmployee_ARGS = "empno";
    public function getEmployee($empno);
    
    const getEmployeesBySalList_QUERY = "sal BETWEEN /*minSal*/ AND /*maxSal*/";
    public function getEmployeesBySalList($minSal, $maxSal);
    
    public function insert(Employee2 $employee);
}
?>