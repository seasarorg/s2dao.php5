<?php

class Department implements Serializable {

    const TABLE = "DEPT";
    private $deptno;
    private $dname;
    private $loc;
    private $versionNo;

    public function __construct() {
    }

    public function serialize(){
        return serialize(array(
                    "deptno" => $this->deptno,
                    "dname" => $this->dname,
                    "loc" => $this->loc,
                    "versionNo" => $this->versionNo,
                ));
    }

    public function unserialize($serialized){
        foreach(unserialize($serialized) as $key => $value){
            $this->$key = $value;
        }
    }

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
        if (!($other instanceof Department) ) return false;
        return $this->getDeptno() == $other->getDeptno();
    }
    
    public function toString() {
        $buf = $this->deptno . ", ";
        $buf .= $this->dname . ", ";
        $buf .= $this->loc . ", ";
        $buf .= $this->versionNo;
        return $buf;
    }

    public function hashCode() {
        return $this->getDeptno();
    }
}

?>
