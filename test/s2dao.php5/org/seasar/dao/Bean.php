<?php

class Department{
}

class HogeBean {
    const TABLE = "HogeTable";
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

?>