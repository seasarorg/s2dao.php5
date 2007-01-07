<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id: $
//
/**
 * @author nowel
 */
interface S2Dao_BeanMetaData extends S2Dao_DtoMetaData {

    public function getTableName();

    /**
     * @return PropertyType
     */
    public function getVersionNoPropertyType();
    
    /**
     * @return String
     */
    public function getVersionNoPropertyName();

    /**
     * @return boolean
     */
    public function hasVersionNoPropertyType();

    /**
     * @return PropertyType
     */
    public function getTimestampPropertyType();

    /**
     * @return String
     */
    public function getTimestampPropertyName();

    /**
     * @return boolean
     */
    public function hasTimestampPropertyType();

    /**
     * @return String
     */
    public function convertFullColumnName($alias);

    /**
     * @return PropertyType
     */
    public function getPropertyTypeByAliasName($aliasName);

    /**
     * @return PropertyType
     */
    public function getPropertyTypeByColumnName($columnName);

    /**
     * @return boolean
     */
    public function hasPropertyTypeByColumnName($columnName);

    /**
     * @return boolean
     */
    public function hasPropertyTypeByAliasName($aliasName);

    /**
     * @return integer
     */
    public function getRelationPropertyTypeSize();

    /**
     * @return RelationPropertyType
     */
    public function getRelationPropertyType($indexOrName);

    /**
     * @return integer
     */
    public function getPrimaryKeySize();

    /**
     * @return String
     */
    public function getPrimaryKey($index);
    
    /**
     * @return String
     */
    public function getAutoSelectList();

    /**
     * @return boolean
     */
    public function isRelation();

    /**
     * @return boolean
     */
    //public function isBeanClassAssignable(ReflectionClass $clazz);

    public function checkPrimaryKey();

    /**
     * @return RelationPropertyType
     */
    public function getManyRelationPropertyType($index);

    /**
     * @return integer
     */
    public function getOneToManyRelationPropertyTypeSize();
}
?>
