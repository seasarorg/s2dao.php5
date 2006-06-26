<?php

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

?>