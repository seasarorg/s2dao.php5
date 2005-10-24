<?php

/**
 * @author Yusuke Hata
 */
interface RelationPropertyType extends PropertyType {

    public function getRelationNo();

    public function getKeySize();
    
    public function getMyKey($index);
    
    public function getYourKey($index);
    
    public function isYourKey($columnName);
    
    public function getBeanMetaData();
}
?>
