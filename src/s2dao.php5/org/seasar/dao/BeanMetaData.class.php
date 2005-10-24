<?php

/**
 * @author nowel
 */
interface BeanMetaData extends DtoMetaData {

    const TABLE = "TABLE";
    const RELNO_SUFFIX = "_RELNO";
    const RELKEYS_SUFFIX = "_RELKEYS";
    const ID_SUFFIX = "_ID";
    const NO_PERSISTENT_PROPS = "NO_PERSISTENT_PROPS";
    const VERSION_NO_PROPERTY = "VERSION_NO_PROPERTY";
    const TIMESTAMP_PROPERTY = "TIMESTAMP_PROPERTY";

    public function getTableName();

    public function getVersionNoPropertyType();

    public function getVersionNoPropertyName();
    
    public function hasVersionNoPropertyType();

    public function getTimestampPropertyType();

    public function getTimestampPropertyName();

    public function hasTimestampPropertyType();

    public function convertFullColumnName($alias);

    public function getPropertyTypeByAliasName($aliasName);

    public function getPropertyTypeByColumnName($columnName);

    public function hasPropertyTypeByColumnName($columnName);

    public function hasPropertyTypeByAliasName($aliasName);

    public function getRelationPropertyTypeSize();

    public function getRelationPropertyType($index);

    public function getPrimaryKeySize();

    public function getPrimaryKey($index);
    
    public function getIdentifierGenerator();

    public function getAutoSelectList();

    public function isRelation();
}
?>
