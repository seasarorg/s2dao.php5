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
 * @package org.seasar.s2dao
 */
interface S2Dao_BeanAnnotationReader {
    
    public function getColumnAnnotation(S2Container_PropertyDesc $pd);

    public function getTableAnnotation();

    public function getVersionNoPropertyNameAnnotation();

    public function getTimestampPropertyName();

    public function getId(S2Container_PropertyDesc $pd);

    public function getNoPersisteneProps();
    
    /**
     * @return ReflectionClass
     */
    public function getRelationBean(S2Container_PropertyDesc $pd);
    
    /**
     * @return RelationType
     */
    public function getRelationType(S2Container_PropertyDesc $pd);
    
    public function getRelationTable(S2Container_PropertyDesc $pd);

    public function hasRelationNo(S2Container_PropertyDesc $pd);

    public function getRelationNo(S2Container_PropertyDesc $pd);

    public function getRelationKey(S2Container_PropertyDesc $pd);
    
    /**
     * @return ValueTypeName
     */
    public function getValueType(S2Container_PropertyDesc $pd);
}
?>
