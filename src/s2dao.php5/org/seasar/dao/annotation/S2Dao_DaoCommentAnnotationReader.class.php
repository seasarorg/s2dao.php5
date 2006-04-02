<?php

class S2Dao_DaoCommentAnnotationReader implements S2Dao_DaoAnnotationReader {
    
    protected $daoBeanDesc;
    
    public function __construct(S2Container_BeanDesc $daoBeanDesc) {
    }

    public function getArgNames(ReflectionMethod $method) {
    }

    public function getQuery(ReflectionMethod $method) {
    }

    public function getBeanClass() {
    }

    public function getNoPersistentProps(ReflectionMethod $method) {
    }

    public function getPersistentProps(ReflectionMethod $method) {
    }

    public function getSQL(ReflectionMethod $method, $dbmsSuffix) {
    }

    private function getProps(ReflectionMethod $method, $constName){
    }
}

?>