<?php

/**
 * @author nowel
 */
class S2Dao_FieldBeanAnnotationReader implements S2Dao_BeanAnnotationReader {

    const TABLE = "TABLE";
    const RELNO_SUFFIX = "_RELNO";
    const RELKEYS_SUFFIX = "_RELKEYS";
    const ID_SUFFIX = "_ID";
    const NO_PERSISTENT_PROPS = "NO_PERSISTENT_PROPS";
    const VERSION_NO_PROPERTY = "VERSION_NO_PROPERTY";
    const TIMESTAMP_PROPERTY = "TIMESTAMP_PROPERTY";
    const COLUMN_SUFFIX = "_COLUMN";
    private $beanDesc_;
    
    public function __construct($beanClass_) {
        $this->beanDesc_ = S2Container_BeanDescFactory::getBeanDesc($beanClass_);
    }

    public function getColumnAnnotation(S2Container_PropertyDesc $pd) {
        $propertyName = $pd->getPropertyName();
        $columnNameKey = $propertyName . self::COLUMN_SUFFIX;
        $columnName = $propertyName;
        if ($this->beanDesc_->hasConstant($columnNameKey)) {
            $columnName = $this->beanDesc_->getConstant($columnNameKey);
        }
        return columnName;
    }

    public function getTableAnnotation() {
        if ($this->beanDesc_->hasConstant(self::TABLE)) {
            return $this->beanDesc_->getConstant(self::TABLE);
        }
        return null;
    }

    public function getVersionNoProteryNameAnnotation() {
        if ($this->beanDesc_->hasConstant(self::VERSION_NO_PROPERTY)) {
            return $this->beanDesc_->getConstant(self::VERSION_NO_PROPERTY);
        }
        return null;
    }

    public function getTimestampPropertyName() {
        if ($this->beanDesc_->hasConstant(self::TIMESTAMP_PROPERTY)) {
            return $this->beanDesc_->getConstant(self::TIMESTAMP_PROPERTY);
        }
        return null;
    }

    public function getId(S2Container_PropertyDesc $pd) {
        $idKey = $pd->getPropertyName() . self::ID_SUFFIX;
        if ($this->beanDesc_->hasConstant($idKey)) {
            return $this->beanDesc_->getConstant($idKey);
        }
        return null;
    }

    public function getNoPersisteneProps() {
        if ($this->beanDesc_->hasConstant(self::NO_PERSISTENT_PROPS)) {
            $s = $this->beanDesc_->getConstant(self::NO_PERSISTENT_PROPS);
            return S2Dao_FieldAnnotationReader::spacetrim(explode(",", $s));
        }
        return null;
    }

    public function getRelationKey(S2Container_PropertyDesc $pd) {
        $propertyName = $pd->getPropertyName();
        $relkeysKey = $propertyName . self::RELKEYS_SUFFIX;
        if($this->beanDesc_->hasConstant($relkeysKey)) {
            return $this->beanDesc_->getConstant($relkeysKey);
        }
        return null;
    }

    public function getRelationNo(S2Container_PropertyDesc $pd) {
        $relnoKey = $pd->getPropertyName() . self::RELNO_SUFFIX;
        return (int)$this->beanDesc_->getConstant($relnoKey);
    }

    public function hasRelationNo(S2Container_PropertyDesc $pd) {
        $relnoKey = $pd->getPropertyName() . self::RELNO_SUFFIX;
        return $this->beanDesc_->hasConstant($relnoKey);
    }
}
?>