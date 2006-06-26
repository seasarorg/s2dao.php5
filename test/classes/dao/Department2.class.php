<?php

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

?>