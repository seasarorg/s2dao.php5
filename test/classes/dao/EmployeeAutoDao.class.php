<?php

interface EmployeeAutoDao {

    const BEAN = "Employee2";
    
    const getEmployee_ARGS = "empno";
    public function getEmployee($empno);
    
    const getEmployeesBySalList_QUERY = "sal BETWEEN /*minSal*/ AND /*maxSal*/";
    public function getEmployeesBySalList($minSal, $maxSal);
    
    const getEmployeeByDeptnoList_ARGS = "deptno";
    const getEmployeeByDeptnoList_ORDER = "deptno asc, empno desc";
    public function getEmployeeByDeptnoList($deptno);
    
    const getEmployeesByEnameJobList_ARGS = "enames, jobs";
    const getEmployeesByEnameJobList_QUERY = "ename IN /*enames*/('SCOTT','MARY') AND job IN /*jobs*/('ANALYST', 'FREE')";
    public function getEmployeesByEnameJobList(S2Dao_ArrayList $enames, S2Dao_ArrayList $jobs);

    public function getEmployeesBySearchConditionList(EmployeeSearchCondition $dto);
    
    const getEmployeesBySearchCondition2List_QUERY = "department.dname = /*dto.department.dname*/'RESEARCH'";
    public function getEmployeesBySearchCondition2List(EmployeeSearchCondition $dto);
    
    public function getEmployeesByEmployeeList(Employee2 $dto);
    
    public function insert(Employee2 $employee);
    
    const insert2_NO_PERSISTENT_PROPS = "job, mgr, hiredate, sal, comm, deptno";
    public function insert2(Employee2 $employee);

    const insert3_PERSISTENT_PROPS = "deptno";
    public function insert3(Employee2 $employee);

    public function update(Employee2 $employee);

    const update2_NO_PERSISTENT_PROPS = "job, mgr, hiredate, sal, comm, deptno";
    public function update2(Employee2 $employee);

    const update3_PERSISTENT_PROPS = "deptno";
    public function update3(Employee2 $employee);

    public function delete(Employee2 $employee);
}
?>