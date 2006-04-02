<?php

class S2Dao_DaoCommentAnnotationReader implements S2Dao_DaoAnnotationReader {

    const ARGS_SUFFIX = 'ARGS';
    const SQL_SUFFIX = 'SQL';
    const QUERY_SUFFIX = 'QUERY';
    const NO_PERSISTENT_PROPS_SUFFIX = 'NO_PERSISTENT_PROPS';
    const PERSISTENT_PROPS_SUFFIX = 'PERSISTENT_PROPS';

    const Anno = 'S2Dao_DaoAnnotation';

    protected $beanClass;
    
    public function __construct(S2Container_BeanDesc $beanDesc) {
        $this->beanClass = $beanDesc->getBeanClass();
    }
    
    public function getBeanClass() {
        $anno = S2Container_Annotations::getAnnotation(self::Anno, $this->beanClass);
        return new ReflectionClass(new $anno->BEAN);
    }

    public function getArgNames(ReflectionMethod $method) {
    }

    public function getQuery(ReflectionMethod $method) {
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