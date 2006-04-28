<?php

/**
 * @author nowel
 */
class S2Dao_BeanCommentAnnotationReader implements S2Dao_BeanAnnotationReader {
    
    const ID_REGEX = '/@Id*\(\"(.*)\"\)/s';
    const COLUMN_REGEX = '/@Column\(\"(.*)\"\)/s';
    const RELATION_REGEX = '/@Relation\(((.*)=(.*)){1,}\)/s';
    const RELKEY_REGEX = '/relationKey\s*=(.*)/s';
    const RELNO_REGEX = '/relationNo\s*=(.+)/s';
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
        if(preg_match(self::COLUMN_REGEX, $this->getComments($pd), $match)){
            return trim($match[1]);
        }
        return $pd->getPropertyName();
    }

    public function getId(S2Container_PropertyDesc $pd) {
        if(preg_match(self::ID_REGEX, $this->getComments($pd), $match)){
            return trim($match[1]);
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

    public function getRelationKey(S2Container_PropertyDesc $pd) {
        if(preg_match(self::RELKEY_REGEX, $this->getComments($pd), $match)){
            return $match[1];
        }
        return null;
    }

    public function getRelationNo(S2Container_PropertyDesc $pd) {
        if(preg_match(self::RELNO_REGEX, $this->getComments($pd), $match)){
            return (int)trim($match[1]);
        }
        return null;
    }

    public function hasRelationNo(S2Container_PropertyDesc $pd) {
        return preg_match(self::RELATION_REGEX, $this->getComments($pd), $match);
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