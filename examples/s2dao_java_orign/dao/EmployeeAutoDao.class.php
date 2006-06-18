<?php
interface EmployeeAutoDao {

    const BEAN = "Employee";

    public function getAllEmployeesList();

    public function getEmployeeByJobDeptnoList($job = null, $deptno = null);

    public function getEmployeeByEmpno($empno);

    const getEmployeesBySalList_QUERY = "EMP.sal BETWEEN ? AND ? ORDER BY EMP.empno";

    public function getEmployeesBySalList($minSal, $maxSal);

    const getEmployeeByDnameList_ARGS = "dname_0";
    public function getEmployeeByDnameList($dname_0);

    public function getEmployeesBySearchConditionList(EmployeeSearchCondition $dto);
    public function getEmployeesBySearchConditionArray(EmployeeSearchCondition $dto);

    public function update(Employee $employee);
}
?>
