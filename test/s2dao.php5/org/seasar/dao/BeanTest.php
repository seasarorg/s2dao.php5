<?php

class Department {
}

class HogeBean {
    const TABLE = "HogeTable";
}

class Hoge2Bean {
    private $id = -1;

    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
}

/**
 * @Bean
 * @NoPersistentProperty(aa, bb)
 */
class FooBean {
    
    /**
     * @Id(assigned)
     */
    private $aa;
    
    /**
     * @Column("BB")
     */
    private $bb;
    
    /**
     * @Relation(relationNo = 0)
     */
    private $cc;
    
    /**
     * @Relation(relationNo = 1, relationKey = "EMP:EMPNO");
     */
    private $dd;
    
    public function __set($name, $param){
    }
    public function getAa(){
    }
    public function getBb(){
    }
    public function getCc(){
    }
    public function getDd(){
    }
}

class AnnotationTestBean1 {

    const TABLE = "TABLE";
    const NO_PERSISTENT_PROPS = "prop2";
    const TIMESTAMP_PROPERTY = "myTimestamp";
    const VERSION_NO_PROPERTY = "myVersionNo";
    const prop1_ID = "sequence, sequenceName=myseq";
    const prop1_COLUMN = "Cprop1";

    private $department;
    private $myTimestamp;

    public function getProp1() {
        return 0;
    }

    public function setProp1($i) {
    }

    public function getProp2() {
        return 0;
    }

    public function setProp2($i) {
    }

    public function getMyTimestamp() {
        return $this->myTimestamp;
    }

    public function setMyTimestamp($myTimestamp) {
        $this->myTimestamp = $myTimestamp;
    }

    const department_RELNO = 0;
    const department_RELKEYS = "DEPTNUM:DEPTNO";

    public function getDepartment() {
        return $this->department;
    }

    public function setDepartment(Department $department) {
        $this->department = $department;
    }

}

class AnnotationTestBean2 {

    const prop1_COLUMN = "Cprop1";

    public function getProp1() {
        return 0;
    }

    public function setProp1($i) {
    }

    public function getProp2() {
        return 0;
    }

    public function setProp2($i) {
    }
}

class Department2 {

    const TABLE = "DEPT2";
    private $deptno;
    private $dname;
    private $loc;
    private $versionNo;


    public function getDeptno() {
        return $this->deptno;
    }

    public function setDeptno($deptno) {
        $this->deptno = $deptno;
    }

    public function getDname() {
        return $this->dname;
    }

    public function setDname($dname) {
        $this->dname = $dname;
    }

    public function getLoc() {
        return $this->loc;
    }

    public function setLoc($loc) {
        $this->loc = $loc;
    }
    
    public function getVersionNo() {
        return $this->versionNo;
    }

    public function setVersionNo($versionNo) {
        $this->versionNo = $versionNo;
    }

    public function equals($other) {
        if ( !($other instanceof Department2) ) return false;
        return $this->getDeptno() == $other->getDeptno();
    }

    public function hashCode() {
        return $this->getDeptno();
    }
    
    public function toString() {
        $buf = "";
        $buf .= $this->deptno . ", ";
        $buf .= $this->dname . ", ";
        $buf .= $this->loc . ", ";
        $buf .= $this->versionNo;
        return $buf;
    }
}

class Employee2 {
    
    const TABLE = "EMP2";
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
        if ( !($other instanceof Employee2) ) return false;
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