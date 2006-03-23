<?php
interface EmployeeAutoDao {

    const BEAN = "Employee";

    public function getAllEmployeesList();

    public function getEmployeeByJobDeptnoList($job, $deptno);

    public function getEmployeeByEmpno($empno);

    const getEmployeesBySalList_QUERY = "sal BETWEEN ? AND ? ORDER BY empno";

    public function getEmployeesBySalList($minSal, $maxSal);

    public function getEmployeeByDnameList($dname_0);

    public function getEmployeesBySearchConditionList(EmployeeSearchCondition $dto);

    public function update(Employee $employee);
}
?>
