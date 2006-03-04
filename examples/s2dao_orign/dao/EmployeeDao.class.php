<?php

interface EmployeeDao {

    const BEAN = "Employee";
    
    public function List_getAllEmployees();

    public function getEmployee($empno);

    public function getCount();

    public function List_getEmployeeByJobDeptno($job, $deptno);
    
    const List_getEmployeeByDeptno_QUERY = "
                /*IF deptno != null*/deptno = /*deptno*/123
                /*ELSE*/ deptno = 123
                /*END*/";
    public function List_getEmployeeByDeptno($deptno);

    public function update(Employee $employee);
}
?>
