<?php

class DepartmentTotalSalary {
    
    const TABLE = "DEPT2";
    private $deptno;
    private $totalSalary;

    public function getDeptno() {
        return $this->deptno;
    }

    public function setDeptno($deptno) {
        $this->deptno = $deptno;
    }

    public function getTotalSalary() {
        return $this->totalSalary;
    }

    public function setTotalSalary($totalSalary) {
        $this->totalSalary = $totalSalary;
    }

    public function toString() {
        $buf = "";
        $buf .= $this->deptno . ", ";
        $buf . $this->totalSalary;
        return $buf;
    }
}
?>