<?php

/**
 * @author nowel
 */
class S2Dao_DtoMetaDataImpl implements S2Dao_DtoMetaData {

    private $beanClass_;
    private $propertyTypes_ = array();
    protected $beanAnnotationReader_;

    public function __construct(ReflectionClass $beanClass,
                                S2Dao_FieldBeanAnnotationReader $beanAnnotationReader) {
        $this->beanAnnotationReader_ = $beanAnnotationReader;
        $this->beanClass_ = $beanClass;
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($beanClass);
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
        if(is_integer($index)){
            $array = array_values($this->propertyTypes_);
            return $array[$index];
        } else {
            $propertyType = $this->propertyTypes_[$index];
            if ($propertyType == null) {
                throw new S2Container_PropertyNotFoundRuntimeException(
                                $this->getBeanClass(), $propertyType);
            }
            return $propertyType;
        }
    }

    public function hasPropertyType($propertyName) {
        return isset($this->propertyTypes_[$propertyName]);
    }

    protected function setupPropertyType(S2Container_BeanDesc $beanDesc) {
        for ($i = 0; $i < $beanDesc->getPropertyDescSize(); $i++) {
            $pd = $beanDesc->getPropertyDesc($i);
            $pt = $this->createPropertyType($beanDesc, $pd);
            $this->addPropertyType($pt);
        }
    }

    protected function createPropertyType(S2Container_BeanDesc $beanDesc,
                                          S2Container_PropertyDesc $propertyDesc) {
        $columnName = $propertyDesc->getPropertyName();
        $ca = $this->beanAnnotationReader_->getColumnAnnotation($propertyDesc);
        if($ca != null){
            $columnName = $ca;
        }
        //$valueType = $propertyDesc->getPropertyType();
        //return new S2Dao_PropertyTypeImpl($propertyDesc, $valueType, $columnName);
        return new S2Dao_PropertyTypeImpl($propertyDesc, null, $columnName);
    }

    protected function addPropertyType(S2Dao_PropertyType $propertyType) {
        $this->propertyTypes_[$propertyType->getPropertyName()] = $propertyType;
    }
}
?>
