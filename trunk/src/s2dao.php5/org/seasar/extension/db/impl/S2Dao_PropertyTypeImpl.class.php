<?php

/**
 * @author nowel
 */
class S2Dao_PropertyTypeImpl implements S2Dao_PropertyType {

    private $propertyDesc_ = null;
    private $propertyName_ = null;
    private $columnName_ = null;
    private $valueType_ = null;
    private $primaryKey_ = false;
    private $persistent_ = true;

    public function __construct($propertyDesc, $valueType = null, $columnName = null) {
        if($propertyDesc instanceof S2Container_PropertyDesc){
            $this->propertyDesc_ = $propertyDesc;
            $this->propertyName_ = $propertyDesc->getPropertyName();
            $this->columnName_ = $propertyDesc->getPropertyName();
            $this->valueType = gettype($propertyDesc->getPropertyType());
        } else if(is_string($propertyDesc)){
            $this->propertyName_ = $propertyDesc;
            $this->columnName_ = $propertyDesc;
        }

        if($valueType !== null){
            $this->valueType_ = $valueType;
        }
        if($columnName !== null){
            $this->columnName_ = $columnName;
        }
    }

    public function getPropertyDesc() {
        return $this->propertyDesc_;
    }

    public function getPropertyName() {
        return $this->propertyName_;
    }

    public function getColumnName() {
        return $this->columnName_;
    }
    
    public function setColumnName($columnName) {
        $this->columnName_ = $columnName;
    }

    public function getValueType() {
        return $this->valueType_;
    }

    public function setValueType($type) {
        $this->valueType_ = $type;
    }

    public function isPrimaryKey() {
        return $this->primaryKey_;
    }

    public function setPrimaryKey($primaryKey) {
        $this->primaryKey_ = $primaryKey;
    }

    public function isPersistent() {
        return $this->persistent_;
    }

    public function setPersistent($persistent) {
        $this->persistent_ = $persistent;
    }
}
?>
