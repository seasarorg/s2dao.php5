<?php

/**
 * @author nowel
 */
class S2Dao_BeanCommentAnnotationReader implements S2Dao_BeanAnnotationReader {
    
    const RELNO_SUFFIX = 'RELNO';
    const RELKEYS_SUFFIX = 'RELKEYS';
    const ID_REGEX = '/@ID\s*=\s*\"(.*)\"/s';
    const COLUMN_REGEX = '/@COLUMN\s*=\s*\"(.*)\"/s';
    const NO_PERSISTENT_PROPS = 'NO_PERSISTENT_PROPS';
    const VERSION_NO_PROPERTY = 'VERSION_NO_PROPERTY';
    const TIMESTAMP_PROPERTY = 'TIMESTAMP_PROPERTY';
    const Anno = 'S2Dao_BeanAnnotation';
    
    private $beanClass;
    
    public function __construct(S2Container_BeanDesc $beanDesc) {
        $this->beanClass = $beanDesc->getBeanClass();
    }
    
    public function getTableAnnotation() {
        $anno = $this->getAnnotation();
        if(isset($anno->TABLE)){
            return $anno->TABLE;
        }
        return null;
    }

    public function getColumnAnnotation(S2Container_PropertyDesc $pd) {
        if(preg_match(self::COLUMN_REGEX, $this->getComments($pd), $match)){
            return $match[1];
        }
        return $pd->getPropertyName();
    }

    public function getId(S2Container_PropertyDesc $pd) {
        if(preg_match(self::ID_REGEX, $this->getComments($pd), $match)){
            return $match[1];
        }
        return null;
    }

    public function getNoPersisteneProps() {
        $anno = $this->getAnnotation();
        if(isset($anno->NO_PERSISTENT_PROPS)){
            return $anno->NO_PERSISTENT_PROPS;
        }
        return null;
    }

    public function getVersionNoPropertyNameAnnotation() {
        $anno = $this->getAnnotation();
        if(isset($anno->VERSION_NO_PROPERTY)){
            return $anno->VERSION_NO_PROPERTY;
        }
        return null;
    }

    public function getTimestampPropertyName() {
        $anno = $this->getAnnotation();
        if(isset($anno->TIMESTAMP_PROPERTY)){
            return $anno->TIMESTAMP_PROPERTY;
        }
        return null;
    }

    public function getRelationKey(S2Container_PropertyDesc $pd) {
        return null;
    }

    public function getRelationNo(S2Container_PropertyDesc $pd) {
        return null;
    }

    public function hasRelationNo(S2Container_PropertyDesc $pd) {
        return null;
    }
    
    private function getComments(S2Container_PropertyDesc $pd){
        $propertyName = $pd->getPropertyName();
        $property = $this->beanClass->getProperty($propertyName);
        return $property->getDocComment();
    }
    
    private function getAnnotation(){
        return S2Container_Annotations::getAnnotation(self::Anno,
                                                      $this->beanClass->getName());
    }
}

?>