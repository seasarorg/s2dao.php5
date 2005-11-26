<?php

/**
 * @author nowel
 */
class S2Dao_DtoMetaDataImpl implements S2Dao_DtoMetaData {

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
        } else {
            $propertyType = $this->propertyTypes_[$index];
            if ($propertyType == null) {
                throw new S2Container_PropertyNotFoundRuntimeException($this->beanClass_,
                                                            $propertyType);
            }
            return $propertyType;
        }
    }

    public function hasPropertyType($propertyName) {
        return isset($this->propertyTypes_[$propertyName]);
    }

    protected function setupPropertyType(S2Container_BeanDesc $beanDesc) {
        for ($i = 0; $i < $beanDesc->getPropertyDescSize(); ++$i) {
            $pd = $beanDesc->getPropertyDesc($i);
            $pt = $this->createPropertyType($beanDesc, $pd);
            $this->addPropertyType($pt);
        }
    }

    protected function createPropertyType(S2Container_BeanDesc $beanDesc,
                                          S2Container_PropertyDesc $propertyDesc) {
        $columnNameKey = $propertyDesc->getPropertyName() . self::COLUMN_SUFFIX;
        $columnName = $propertyDesc->getPropertyName();
        if ($beanDesc->hasConstant($columnNameKey)) {
            $columnName = $beanDesc->getConstant($columnNameKey);
        }
        $valueType = $propertyDesc->getPropertyType();
        return new S2Dao_PropertyTypeImpl($propertyDesc, $valueType, $columnName);
    }

    protected function addPropertyType(S2Dao_PropertyType $propertyType) {
        $this->propertyTypes_[$propertyType->getPropertyName()] = $propertyType;
    }
}
?>
