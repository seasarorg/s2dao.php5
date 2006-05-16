<?php

/**
 * @author nowel
 */
interface S2Dao_RelationPropertyType extends S2Dao_PropertyType {
    public function getRelationNo();
    public function getKeySize();
    public function getMyKey($index);
    public function getYourKey($index);
    public function isYourKey($columnName);
    public function getBeanMetaData();
}
?>
