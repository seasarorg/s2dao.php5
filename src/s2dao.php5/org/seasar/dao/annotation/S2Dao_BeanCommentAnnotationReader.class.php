<?php

/**
 * @author nowel
 */
class S2Dao_BeanCommentAnnotationReader implements S2Dao_BeanAnnotationReader {
    
    const Anno = 'Bean';
    private $beanClass;
    
    public function __construct(S2Container_BeanDesc $beanDesc) {
        $this->beanClass = $beanDesc->getBeanClass();
    }
    
    public function getTableAnnotation() {
        $anno = $this->getAnnotation();
        if($anno->table != ''){
            return $anno->table;
        }
        return null;
    }

    public function getColumnAnnotation(S2Container_PropertyDesc $pd) {
        $anno = $this->getPropertyAnnotation('Column', $pd);
        if($anno !== null){
            return $anno->value;
        }
        return $pd->getPropertyName();
    }

    public function getId(S2Container_PropertyDesc $pd) {
        $anno = $this->getPropertyAnnotation('Id', $pd);
        if($anno !== null){
            return $anno->value;
        }
        return null;
    }

    public function getNoPersisteneProps() {
        $anno = $this->getAnnotation();
        if(isset($anno->noPersistentProperty)){
            return $anno->noPersistentProperty;
        }
        return null;
    }

    public function getVersionNoPropertyNameAnnotation() {
        $anno = $this->getAnnotation();
        if(isset($anno->versionNoProperty)){
            return $anno->versionNoProperty;
        }
        return null;
    }

    public function getTimestampPropertyName() {
        $anno = $this->getAnnotation();
        if(isset($anno->timeStampProperty)){
            return $anno->timeStampProperty;
        }
        return null;
    }
    
    public function getRelationNo(S2Container_PropertyDesc $pd) {
        $anno = $this->getPropertyAnnotation('Relation', $pd);
        if(isset($anno->relationNo)){
            return (int)$anno->relationNo;
        }
        return null;
    }

    public function getRelationKey(S2Container_PropertyDesc $pd) {
        $anno = $this->getPropertyAnnotation('Relation', $pd);
        if(isset($anno->relationKey)){
            return $anno->relationKey;
        }
        return null;
    }

    public function hasRelationNo(S2Container_PropertyDesc $pd) {
        return $this->getPropertyAnnotation('Relation', $pd) !== null;
    }
    
    private function getPropertyAnnotation($annoType, S2Container_PropertyDesc $pd){
        $propertyName = $pd->getPropertyName();
        if(S2Container_Annotations::isAnnotationPresent($annoType,
                                                      $this->beanClass,
                                                      $propertyName)){
            return S2Container_Annotations::getAnnotation($annoType,
                                                          $this->beanClass,
                                                          $propertyName);
        }
        return null;
    }
    
    private function getAnnotation(){
        return S2Container_Annotations::getAnnotation(self::Anno,
                                   $this->beanClass->getName());
    }
    
}

?>