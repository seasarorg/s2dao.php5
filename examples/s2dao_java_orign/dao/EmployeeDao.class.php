<?php

interface EmployeeDao {

    const BEAN = "Employee";
    
    public function getAllEmployeesList();

    public function getEmployee($empno);

    public function getCount();

    public function getEmployeeByJobDeptnoList($job, $deptno);
    
    const getEmployeeByDeptnoList_QUERY = "
                /*IF deptno !== null*/EMP.DEPTNO = /*deptno*/123
                --ELSE 1 = 1
                /*END*/";
    public function getEmployeeByDeptnoList($deptno);

    public function update(Employee $employee);
}
?>
