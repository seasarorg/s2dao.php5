<?php

class Employee implements Serializable {

    const TABLE = "EMP";
    const department_RELNO = 0;
    const timestamp_COLUMN = "TSTAMP";
    
    private $empno;
    private $ename;
    private $job;
    private $mgr;
    private $hiredate;
    private $sal;
    private $comm;
    private $deptno;
    private $timestamp;
    private $department;

    public function serialize(){
        $prop = array();
        foreach(get_class_vars(__CLASS__) as $key => $value){
            $prop[$key] = $value;
        }
        return serialize($prop);
    }

    public function unserialize($serialized){
        foreach(unserialize($serialized) as $key => $value){
            $this->$key = $value;
        }
    }

    public function __construct($empno = null) {
        if(!is_null($empno)){
            $this->empno = $empno;
        }
    }

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

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    public function getDepartment() {
        return $this->department;
    }

    public function setDepartment(Department $department) {
        $this->department = $department;
    }

    public function equals($other) {
        if (!($other instanceof Employee)) return false;
        return $this->getEmpno() == $other->getEmpno();
    }

    public function toString() {
        $buf = $this->empno . ", ";
        $buf .= $this->ename . ", ";
        $buf .= $this->job . ", ";
        $buf .= $this->mgr . ", ";
        $buf .= $this->hiredate . ", ";
        $buf .= $this->sal . ", ";
        $buf .= $this->comm . ", ";
        $buf .= $this->deptno . ", ";
        $buf .= $this->timestamp . " {";
        if($this->department != null){
            $buf .=  $this->department->toString();
        } else {
            $buf .= "null";
        }
        $buf .= "}";
        return $buf;
    }

    public function hashCode() {
        return $this->getEmpno();
    }

}
?>
