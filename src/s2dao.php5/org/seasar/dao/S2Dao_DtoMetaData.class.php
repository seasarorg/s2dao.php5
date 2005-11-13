<?php

/**
 * @author nowel
 */
interface S2Dao_DtoMetaData {
    //const COLUMN_SUFFIX = "_COLUMN";
    public function getBeanClass();
    public function getPropertyTypeSize();
    public function getPropertyType($prop);
    public function hasPropertyType($propertyName);
}
?>
