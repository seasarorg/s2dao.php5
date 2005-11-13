<?php

/**
 * @author nowel
 */
class S2Dao_FieldAnnotationReader implements S2Dao_DaoAnnotationReader {

    const BEAN = "BEAN";
    const ARGS_SUFFIX = "_ARGS";
    const SQL_SUFFIX = "_SQL";
    const QUERY_SUFFIX = "_QUERY";
    const NO_PERSISTENT_PROPS_SUFFIX = "_NO_PERSISTENT_PROPS";
    const PERSISTENT_PROPS_SUFFIX = "_PERSISTENT_PROPS";

    protected $daoBeanDesc_;

    public function __construct(S2Container_BeanDesc $daoBeanDesc) {
        $this->daoBeanDesc_ = $daoBeanDesc;
    }

    public function getArgNames($methodName) {
        $argsKey = $methodName . self::ARGS_SUFFIX;
        if ($this->daoBeanDesc_->hasConstant($argsKey)) {
            $argNames = $this->daoBeanDesc_->getConstant($argsKey);
            return $this->spacetrim(explode(",", $argNames));
        } else {
            return array();
        }
    }

    public function getQuery($methodName) {
        $key = $methodName . self::QUERY_SUFFIX;
        if ($this->daoBeanDesc_->hasConstant($key)) {
            return $this->daoBeanDesc_->getConstant($key);
        } else {
            return null;
        }
    }

    public function getBeanClass() {
        $beanField = $this->daoBeanDesc_->getConstant(self::BEAN);
        return new ReflectionClass(new $beanField);
    }

    public function getNoPersistentProps($methodName) {
        return $this->getProps($methodName, $methodName . self::NO_PERSISTENT_PROPS_SUFFIX);
    }

    public function getPersistentProps($methodName) {
        return $this->getProps($methodName, $methodName . self::PERSISTENT_PROPS_SUFFIX);
    }

    private function getProps($methodName, $fieldName){
        if ($this->daoBeanDesc_->hasConstant($fieldName)) {
            $s = $this->daoBeanDesc_->getConstant($fieldName);
            return $this->spacetrim(explode(",", $s));
        }
        return null;
    }
    public function getSQL($methodName, $dbmsSuffix) {
        $key = $methodName . $dbmsSuffix . self::SQL_SUFFIX;
        if ($this->daoBeanDesc_->hasConstant($key)) {
            return $this->daoBeanDesc_->getConstant($key);
        }
        $key = $methodName . self::SQL_SUFFIX;
        if ($this->daoBeanDesc_->hasConstant($key)) {
            return $this->daoBeanDesc_->getConstant($key);
        }
        return null;
    }
    
    private function spacetrim($elem){
        $ret = array();
        foreach($elem as $key => $value){
            $ret[$key] = trim($value);
        }
        return $ret;
    }
}
?>
