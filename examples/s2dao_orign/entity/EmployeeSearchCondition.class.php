<?php

class EmployeeSearchCondition {

    const dname_COLUMN = "dname_0";

    private $job;
    private $dname;
    
    public function getDname() {
        return $this->dname;
    }
    
    public function setDname($dname) {
        $this->dname = $dname;
    }
    
    public function getJob() {
        return $this->job;
    }
    
    public function setJob($job) {
        $this->job = $job;
    }
}

?>
