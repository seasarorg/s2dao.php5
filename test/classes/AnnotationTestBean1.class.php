<?php

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

?>