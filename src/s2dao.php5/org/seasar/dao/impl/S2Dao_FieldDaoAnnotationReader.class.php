<?php

/**
 * @author nowel
 */
class S2Dao_FieldDaoAnnotationReader implements S2Dao_DaoAnnotationReader {

    const BEAN = "BEAN";
    const ARGS_SUFFIX = "_ARGS";
    const SQL_SUFFIX = "_SQL";
    const QUERY_SUFFIX = "_QUERY";
    const NO_PERSISTENT_PROPS_SUFFIX = "_NO_PERSISTENT_PROPS";
    const PERSISTENT_PROPS_SUFFIX = "_PERSISTENT_PROPS";
    protected $daoBeanDesc_;
    
    public function __construct(S2Container_BeanDesc $daoBeanDesc_) {
        $this->daoBeanDesc_ = $daoBeanDesc_;
    }

    public function getArgNames(ReflectionMethod $method) {
        $argsKey = $method->getName() . self::ARGS_SUFFIX;
        if ($this->daoBeanDesc_->hasConst(argsKey)) {
            $argNames = $this->daoBeanDesc_->getConstant($argsKey);
            return S2Dao_FieldAnnotationReader::spacetrim(explode(",", $argNames));
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

    public function getNoPersistentProps(ReflectionMethod $method) {
        return $this->getProps($method, $method->getName() . self::NO_PERSISTENT_PROPS_SUFFIX);
    }

    public function getPersistentProps(ReflectionMethod $method) {
        return $this->getProps($method, $method->getName() . self::PERSISTENT_PROPS_SUFFIX);
    }

    public function getSQL(ReflectionMethod $method, $dbmsSuffix) {
        $key = $method->getName() . $dbmsSuffix . self::SQL_SUFFIX;
        if ($this->daoBeanDesc_->hasConstant($key)) {
            return $this->daoBeanDesc_->getConstant($key);
        }
        $key = $method->getName() . self::SQL_SUFFIX;
        if ($this->daoBeanDesc_->hasConstant($key)) {
            return $this->daoBeanDesc_->getConstant($key);
        }
        return null;
    }

    private function getProps(ReflectionMethod $method, $constName){
        if ($this->daoBeanDesc_->hasConstant($constName)) {
            $s = $this->daoBeanDesc_->getConstant($constName);
            return S2Dao_FieldAnnotationReader::spacetrim(explode(",", $s));
        }
        return null;
    }
}
?>
