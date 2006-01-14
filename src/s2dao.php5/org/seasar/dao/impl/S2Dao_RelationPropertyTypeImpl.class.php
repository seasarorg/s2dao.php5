<?php

/**
 * @author nowel
 */
class S2Dao_RelationPropertyTypeImpl extends S2Dao_PropertyTypeImpl implements S2Dao_RelationPropertyType {

    protected $relationNo_;
    protected $myKeys_ = array();
    protected $yourKeys_ = array();
    protected $beanMetaData_;

    public function __construct(S2Container_PropertyDesc $propertyDesc,
                                $relationNo = null, $myKeys = null, $yourKeys = null,
                                S2Dao_BeanMetaData $beanMetaData){

        parent::__construct($propertyDesc);
        $this->relationNo_ = $relationNo;
        $this->myKeys_ = $myKeys;
        $this->yourKeys_ = $yourKeys;
        $this->beanMetaData_ = $beanMetaData;
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
            if (strcasecmp($columnName, $this->getYourKey($i)) == 0 ){
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
