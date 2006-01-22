<?php
interface EmployeeAutoDao {

    const BEAN = "Employee";

    public function List_getAllEmployees();

    public function List_getEmployeeByJobDeptno($job, $deptno);

    public function getEmployeeByEmpno($empno);

    const List_getEmployeesBySal_QUERY = "sal BETWEEN ? AND ? ORDER BY empno";

    public function List_getEmployeesBySal($minSal, $maxSal);

    public function List_getEmployeeByDname($dname_0);

    public function List_getEmployeesBySearchCondition(EmployeeSearchCondition $dto);

    public function update(Employee $employee);
}
?>
