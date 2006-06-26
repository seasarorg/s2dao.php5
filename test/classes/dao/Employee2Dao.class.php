<?php

interface Employee2Dao {
    
    const BEAN = "Employee2";
    
    const updateSal_SQL = "update EMP2 set SAL = SAL * 2 where ENAME LIKE /*ename*/'ABC'";
    public function updateSal($ename);
    
    public function getAllEmployeesList();
    
    public function getEmployee($empno);
    
    public function getEmployeesByDeptnoArray($deptno);

    public function getEmployeeByPagerConditionList(S2Dao_DefaultPagerCondition $dto);
    
    public function getCount();
    
    const insert_ARGS = "empno, ename";
    public function insert($empno, $ename);
    
    public function update(Employee2 $employee);
}

?>