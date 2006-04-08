<?php
interface EmployeeAutoDao {

    const BEAN = "Employee";

    public function getAllEmployeesList();

    public function getEmployeeByJobDeptnoList($job = null, $deptno = null);

    public function getEmployeeByEmpno($empno);

    // FIXME
    //const getEmployeesBySalList_QUERY = "EMP.sal BETWEEN ? AND ? ORDER BY empno";

    public function getEmployeesBySalList($minSal, $maxSal);

    public function getEmployeeByDnameList($dname_0);

    public function getEmployeesBySearchConditionList(EmployeeSearchCondition $dto);

    public function update(Employee $employee);
}
?>
