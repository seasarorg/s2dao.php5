<?php

/**
 * @author Yusuke Hata
 */
class PropertyTypeImpl implements PropertyType {

	private $propertyDesc_ = null;
	private $propertyName_ = null;
	private $columnName_ = null;
	private $valueType_ = null;
	private $primaryKey_ = false;
	private $persistent_ = true;

	public function __construct($propertyDesc, $valueType = null, $columnName = null) {
        if( $propertyDesc instanceof PropertyDesc && isset($valueType, $columnName) ){
            $this->propertyDesc_ = $propertyDesc;
            $this->propertyName_ = $propertyDesc->getPropertyName();
            $this->valueType_ = $valueType;
            $this->columnName_ = $columnName;
        } else if( $propertyDesc instanceof PropertyDesc
                            && isset($valueType) && is_null($columnName) ){
            $this->propertyDesc_ = $propertyDesc;
            $this->valueType_ = $valueType;
            $this->columnName_ = $propertyDesc->getPropertyName();
        } else if( is_string($propertyDesc) && isset($valueType) ){
            $this->propertyName_ = $propertyDesc;
            $this->valueType_ = $valueType;
            $this->columnName_ = $propertyDesc;
        } else {
            $this->propertyDesc_ = $propertyDesc;
            $this->valueType_ = "ValueTypes";
            $this->propertyName_ = $propertyDesc->getPropertyName();
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
