<?php

class Search {
    const TABLE = "SEARCH";
    
    private $deptno;
    private $dname;
    private $loc;
    private $versionNo;
    
    public function setDeptNo($deptno){
        $this->deptno = $deptno;
    }
    
    public function getDeptNo(){
        return $this->deptno;
    }
    
    public function setDname($dname){
        $this->dname = $dname;
    }
    
    public function getDname(){
        return $this->dname;
    }
    
    public function setLoc($loc){
        $this->loc = $loc;
    }
    
    public function getLoc(){
        return $this->loc;
    }
    
    public function setVersionNo($versionNo){
        $this->versionNo = $versionNo;
    }
    
    public function getVersionNo(){
        return $this->versionNo;
    }
}

?>