<?php

class Employee4 {
    
    const TABLE = "EMP2";
    const parent_RELNO = 0;
    const parent_RELKEYS = "mgr:empno";

    private $empno;
    private $ename;
    private $job;
    private $mgr;
    private $hiredate;
    private $sal;
    private $comm;
    private $deptno;
    private $department;

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

    public function getManager() {
        return $this->mgr;
    }

    public function setManager($mgr) {
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
    
    public function getDepartment() {
            return $this->department;
    }
    
    public function setDepartment(Department2 $department) {
           $this->department = $department;
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
        $buf .= $this->department->toString() . "}";
        return $buf;
    }
}