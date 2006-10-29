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
class S2Dao_DtoMetaDataImpl implements S2Dao_DtoMetaData {

    private $beanClass;

    private $propertyTypes = null;

    protected $beanAnnotationReader;

    public function __construct(ReflectionClass $beanClass,
                                S2Dao_BeanAnnotationReader $beanAnnotationReader) {
        $this->setBeanClass($beanClass);
        $this->setBeanAnnotationReader($beanAnnotationReader);
        $this->propertyTypes = new S2Dao_CaseInsensitiveMap();
        $this->initialize();
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
     * @see org.seasar.dao.DtoMetaData#getPropertyType(int)
     * @return PropertyType
     */
    public function getPropertyType($index) {
        if(is_integer($index)){
            return $this->propertyTypes->get($index);
        }
        $propertyName = $index;
        $propertyType = $this->propertyTypes->get($propertyName);
        if ($propertyType === null) {
            throw new S2Dao_PropertyNotFoundRuntimeException($this->beanClass,
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
        return new S2Dao_PropertyTypeImpl($propertyDesc, null, $columnName);
    }
    
    private function getColumnName(S2Container_PropertyDesc $propertyDesc) {
        $ca = $this->beanAnnotationReader->getColumnAnnotation($propertyDesc);
        if ($ca !== null) {
            return $ca;
        }
        return $propertyDesc->getPropertyName();
    }

    protected function addPropertyType(S2Dao_PropertyType $propertyType) {
        $propertyTypes->put($propertyType->getPropertyName(), $propertyType);
    }

    public function setBeanAnnotationReader(S2Dao_BeanAnnotationReader $beanAnnotationReader) {
        $this->beanAnnotationReader = $beanAnnotationReader;
    }
}
?>
