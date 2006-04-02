<?php

/**
 * @author nowel
 */
class S2Dao_BeanCommentAnnotationReader implements S2Dao_BeanAnnotationReader {
    
    private $beanDesc;
    
    public function __construct(S2Container_BeanDesc $beanDesc) {
        $this->beanDesc = $beanDesc;
    }

    public function getColumnAnnotation(S2Container_PropertyDesc $pd) {
    }

    public function getTableAnnotation() {
    }

    public function getVersionNoPropertyNameAnnotation() {
    }

    public function getTimestampPropertyName() {
    }

    public function getId(S2Container_PropertyDesc $pd) {
    }

    public function getNoPersisteneProps() {
    }

    public function getRelationKey(S2Container_PropertyDesc $pd) {
    }

    public function getRelationNo(S2Container_PropertyDesc $pd) {
    }

    public function hasRelationNo(S2Container_PropertyDesc $pd) {
    }
    
}

?>