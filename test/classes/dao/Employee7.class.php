<?php

class Employee7 {
    
    const TABLE = "EMP3";
    const department_RELNO = 0;
    const department2_RELNO = 1;

    private $empno;
    private $ename;
    private $job;
    private $mgr;
    private $hiredate;
    private $sal;
    private $comm;
    private $deptno;
    private $password;
    private $dummy;
    private $department;
    private $department2;

    public function getEmpno() {
        return $this->empno;
    }

    public function setEmpno($empno) {
        $this->empno = $empno;
    }

    public function getEname() {
        return $this->ename;
    }

    public function setEname($ename) {
        $this->ename = $ename;
    }

    public function getJob() {
        return $this->job;
    }

    public function setJob($job) {
        $this->job = $job;
    }

    public function getMgr() {
        return $this->mgr;
    }

    public function setMgr($mgr) {
        $this->mgr = $mgr;
    }

    public function getHiredate() {
        return $this->hiredate;
    }

    public function setHiredate($hiredate) {
        $this->hiredate = $hiredate;
    }

    public function getSal() {
        return $this->sal;
    }

    public function setSal($sal) {
        $this->sal = $sal;
    }

    public function getComm() {
        return $this->comm;
    }

    public function setComm($comm) {
        $this->comm = $comm;
    }

    public function getDeptno() {
        return $this->deptno;
    }

    public function setDeptno($deptno) {
        $this->deptno = $deptno;
    }
    
    public function getPassword() {
        return $this->password;
    }
    
    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function getDummy() {
        return $this->dummy;
    }
    
    public function setDummy($dummy) {
        $this->dummy = $dummy;
    }
    
    public function getDepartment() {
        return $this->department;
    }
    
    public function setDepartment(Department2 $department) {
        $this->department = $department;
    }
    
    public function getDepartment2() {
        return $this->department2;
    }
    
    public function setDepartment2(Department2 $department2) {
        $this->department2 = $department2;
    }

    public function equals($other) {
        if ( !($other instanceof Employee7) ) return false;
        return $this->getEmpno() == $other->getEmpno();
    }

    public function hashCode() {
        return $this->getEmpno();
    }
    
    public function toString() {
        $buf = "";
        $buf .= $this->empno . ", ";
        $buf .= $this->ename . ", ";
        $buf .= $this->job . ", ";
        $buf .= $this->mgr . ", ";
        $buf .= $this->hiredate . ", ";
        $buf .= $this->sal . ", ";
        $buf .= $this->comm . ", ";
        $buf .= $this->deptno . " {";
        $buf .= $this->department . "}";
        return $buf;
    }
}

?>