<?php

/**
 * @author nowel
 */
class DtoMetaDataImpl implements DtoMetaData {

    private $beanClass_;
    private $propertyTypes_ = null;

    const COLUMN_SUFFIX = "_COLUMN";

    public function __construct($beanClass) {
        $this->propertyTypes_ = array();
        $this->beanClass_ = $beanClass;
        $beanDesc = BeanDescFactory::getBeanDesc($beanClass);
        $this->setupPropertyType($beanDesc);
    }

    public function getBeanClass() {
        return $this->beanClass_;
    }

    protected function setBeanClass($beanClass) {
        $this->beanClass_ = $beanClass;
    }

    public function getPropertyTypeSize() {
        return count($this->propertyTypes_);
    }

    public function getPropertyType($index) {
        if( is_integer($index) ){
            $arrays = array_values($this->propertyTypes_);
            return $arrays[$index];
            //return $this->propertyTypes_->get($index);
        } else {
            $propertyType = $this->propertyTypes_[$index];
            //$propertyType = $this->propertyTypes_->get($index);
            if ($propertyType == null) {
                throw new PropertyNotFoundRuntimeException($this->beanClass_,
                                                            $propertyType);
            }
            return $propertyType;
        }
    }

    public function hasPropertyType($propertyName) {
        return isset($this->propertyTypes_[$propertyName]);
        //return $this->propertyTypes_->get($propertyName) != null;
    }

    protected function setupPropertyType(BeanDesc $beanDesc) {
        for ($i = 0; $i < $beanDesc->getPropertyDescSize(); ++$i) {
            $pd = $beanDesc->getPropertyDesc($i);
            $pt = $this->createPropertyType($beanDesc, $pd);
            $this->addPropertyType($pt);
        }
    }

    protected function createPropertyType(BeanDesc $beanDesc,
                                          PropertyDesc $propertyDesc) {
        $columnNameKey = $propertyDesc->getPropertyName() . self::COLUMN_SUFFIX;
        $columnName = $propertyDesc->getPropertyName();
        if ($beanDesc->hasConstant($columnNameKey)) {
            $columnName = $beanDesc->getConstant($columnNameKey);
        }
        //$valueType = ValueTypes::getValueType($propertyDesc->getPropertyType());
        $valueType = $propertyDesc->getPropertyType();
        return new PropertyTypeImpl($propertyDesc, $valueType, $columnName);
    }

    protected function addPropertyType(PropertyType $propertyType) {
        $this->propertyTypes_[$propertyType->getPropertyName()] = $propertyType;
        //$this->propertyTypes_->put($propertyType->getPropertyName(), $propertyType);
    }
}
?>
