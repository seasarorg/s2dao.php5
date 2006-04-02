<?php

/**
 * @author nowel
 */
class S2Dao_BeanCommentAnnotationReader implements S2Dao_BeanAnnotationReader {
    
    const Anno = 'S2Dao_BeanAnnotation';
    
    private $beanClass;
    
    public function __construct(S2Container_BeanDesc $beanDesc) {
        $this->beanClass = $beanDesc->getBeanClass();
    }
    
    public function getTableAnnotation() {
        $anno = S2Container_Annotations::getAnnotations($this->beanClass->getName());
        return $anno[self::Anno]->TABLE;
    }

    public function getColumnAnnotation(S2Container_PropertyDesc $pd) {
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