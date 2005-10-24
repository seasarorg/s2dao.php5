<?php

/**
 * @author Yusuke Hata
 */
class RelationPropertyTypeImpl extends PropertyTypeImpl implements RelationPropertyType {

    protected $relationNo_;
    protected $myKeys_ = array();
    protected $yourKeys_ = array();
    protected $beanMetaData_;

    public function __construct(PropertyDesc $propertyDesc, $relationNo = null,
                                $myKeys = null, $yourKeys = null,
                                $dbMetaData = null, $dbms = null){

        parent::__construct($propertyDesc);
        if( isset($relationNo, $myKeys, $dbMetaData, $dbms) ){
            $this->relationNo_ = $relationNo;
            $this->myKeys_ = $myKeys;
            $this->yourKeys_ = $yourKeys;
            $beanClass = $propertyDesc->getPropertyType();
            $this->beanMetaData_ = new BeanMetaDataImpl($beanClass, $dbMetaData, $dbms, true);
        }
    }

    public function getRelationNo() {
        return $this->relationNo_;
    }

    public function getKeySize() {
        if (count($this->myKeys_) > 0) {
            return count($this->myKeys_);
        } else {
            return $this->beanMetaData_->getPrimaryKeySize();
        }
    }

    public function getMyKey($index) {
        if (count($this->myKeys_) > 0) {
            return $this->myKeys_[$index];
        } else {
            return $this->beanMetaData_->getPrimaryKey($index);
        }
    }

    public function getYourKey($index) {
        if (count($this->yourKeys_) > 0) {
            return $this->yourKeys_[$index];
        } else {
            return $this->beanMetaData_->getPrimaryKey($index);
        }
    }
    
    public function isYourKey($columnName) {
        for ($i = 0; $i < $this->getKeySize(); ++$i) {
            if (strcasecmp($columnName,$this->getYourKey($i)) == 0 ){
                return true;
            }
        }
        return false;
    }
    
    public function getBeanMetaData() {
        return $this->beanMetaData_;
    }
}
?>
