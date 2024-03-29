<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
 * @package org.seasar.s2dao.impl
 */
class S2Dao_DtoMetaDataImpl implements S2Dao_DtoMetaData {

    private $beanClass;
    private $propertyTypes;
    private $_propertyTypes = array();
    private $_propertyTypesIndex = 0;
    protected $beanAnnotationReader;
    private $valueTypeFactory;
    
    public function __construct(){
        $this->__construct0();
        $args = func_get_args();
        if(1 < func_num_args()){
            $this->__call('__construct1', $args);
        }
    }
    
    public function __construct0(){
        $this->propertyTypes = new S2Dao_CaseInsensitiveMap();
    }

    public function __construct1(ReflectionClass $beanClass,
                                 S2Dao_BeanAnnotationReader $beanAnnotationReader,
                                 S2Dao_ValueTypeFactory $valueTypeFactory) {
        $this->setBeanClass($beanClass);
        $this->setBeanAnnotationReader($beanAnnotationReader);
        $this->valueTypeFactory = $valueTypeFactory;
        $this->initialize();
    }
    
    private function __call($name, $args){
        if(method_exists($this, $name)){
            return call_user_func_array(array($this, $name), $args);
        }
    }

    public function initialize() {
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($this->getBeanClass());
        $this->setupPropertyType($beanDesc);
    }
    
    /**
     * @see org.seasar.dao.DtoMetaData#getBeanClass()
     * @return ReflectionClass
     */
    public function getBeanClass() {
        return $this->beanClass;
    }

    public function setBeanClass(ReflectionClass $beanClass) {
        $this->beanClass = $beanClass;
    }

    /**
     * @see org.seasar.dao.DtoMetaData#getPropertyTypeSize()
     */
    public function getPropertyTypeSize() {
        return $this->propertyTypes->size();
    }

    /**
     * @return PropertyType
     * @see org.seasar.dao.DtoMetaData#getPropertyType(mixed)
     * @throws S2Container_PropertyNotFoundRuntimeException
     */
    public function getPropertyType($propertyName) {
        if(is_integer($propertyName)){
            return $this->_propertyTypes[$propertyName];
        }
        $propertyType = $this->propertyTypes->get($propertyName);
        if ($propertyType === null) {
            throw new S2Container_PropertyNotFoundRuntimeException($this->beanClass,
                                                             $propertyName);
        }
        return $propertyType;
    }

    /**
     * @see org.seasar.dao.DtoMetaData#hasPropertyType(string)
     */
    public function hasPropertyType($propertyName) {
        return $this->propertyTypes->get($propertyName) !== null;
    }

    protected function setupPropertyType(S2Container_BeanDesc $beanDesc) {
        $size = $beanDesc->getPropertyDescSize();
        for ($i = 0; $i < $size; ++$i) {
            $pd = $beanDesc->getPropertyDesc($i);
            $pt = $this->createPropertyType($beanDesc, $pd);
            $this->addPropertyType($pt);
        }
    }

    /**
     * @return PropertyType
     */
    protected function createPropertyType(S2Container_BeanDesc $beanDesc,
                                          S2Container_PropertyDesc $propertyDesc) {
        $columnName = $this->getColumnName($propertyDesc);
        $valueType = $this->getValueType($propertyDesc);
        return new S2Dao_PropertyTypeImpl($propertyDesc, $valueType, $columnName);
    }
    
    private function getColumnName(S2Container_PropertyDesc $propertyDesc) {
        $ca = $this->beanAnnotationReader->getColumnAnnotation($propertyDesc);
        if ($ca !== null) {
            return $ca;
        }
        return $propertyDesc->getPropertyName();
    }
    
    /**
     * @return S2Dao_ValueType
     */
    protected function getValueType(S2Container_PropertyDesc $propertyDesc) {
        $valueTypeName = $this->beanAnnotationReader->getValueType($propertyDesc);
        $valueTypeFactory = $this->getValueTypeFactory();
        if ($valueTypeName !== null) {
            return $valueTypeFactory->getValueTypeByName($valueTypeName);
        }
        return $valueTypeFactory->getValueTypeByClass($propertyDesc->getPropertyType());
    }

    protected function addPropertyType(S2Dao_PropertyType $propertyType) {
        $this->_propertyTypes[] = $propertyType;
        $this->propertyTypes->put($propertyType->getPropertyName(), $propertyType);
    }

    public function setBeanAnnotationReader(S2Dao_BeanAnnotationReader $beanAnnotationReader) {
        $this->beanAnnotationReader = $beanAnnotationReader;
    }
    
    /**
     * @return ValueTypeFactory
     */
    protected function getValueTypeFactory() {
        return $this->valueTypeFactory;
    }

    public function setValueTypeFactory(S2Dao_ValueTypeFactory $valueTypeFactory) {
        $this->valueTypeFactory = $valueTypeFactory;
    }
}
?>
