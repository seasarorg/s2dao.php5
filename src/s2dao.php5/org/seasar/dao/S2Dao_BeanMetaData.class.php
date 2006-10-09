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

    const TABLE = 'TABLE';
    const RELNO_SUFFIX = '_RELNO';
    const RELKEYS_SUFFIX = '_RELKEYS';
    const ID_SUFFIX = '_ID';
    const NO_PERSISTENT_PROPS = 'NO_PERSISTENT_PROPS';
    const VERSION_NO_PROPERTY = 'VERSION_NO_PROPERTY';
    const TIMESTAMP_PROPERTY = 'TIMESTAMP_PROPERTY';

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
