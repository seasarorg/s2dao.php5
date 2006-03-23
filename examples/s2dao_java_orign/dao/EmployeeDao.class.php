<?php

interface EmployeeDao {

    const BEAN = "Employee";
    
    public function getAllEmployeesList();

    public function getEmployee($empno);

    public function getCount();

    public function getEmployeeByJobDeptnoList($job, $deptno);
    
    const getEmployeeByDeptnoList_QUERY = "
                /*IF deptno != null*/deptno = /*deptno*/123
                /*ELSE*/ deptno = 123
                /*END*/";
    public function getEmployeeByDeptnoList($deptno);

    public function update(Employee $employee);
}
?>
