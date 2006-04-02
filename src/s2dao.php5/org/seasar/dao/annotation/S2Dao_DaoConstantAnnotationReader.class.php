<?php

/**
 * @author nowel
 */
class S2Dao_DaoConstantAnnotationReader implements S2Dao_DaoAnnotationReader {
    
    const BEAN = 'BEAN';
    const ARGS_SUFFIX = '_ARGS';
    const SQL_SUFFIX = '_SQL';
    const QUERY_SUFFIX = '_QUERY';
    const NO_PERSISTENT_PROPS_SUFFIX = '_NO_PERSISTENT_PROPS';
    const PERSISTENT_PROPS_SUFFIX = '_PERSISTENT_PROPS';

    protected $daoBeanDesc;
    
    public function __construct(S2Container_BeanDesc $daoBeanDesc) {
        $this->daoBeanDesc = $daoBeanDesc;
    }

    public function getArgNames(ReflectionMethod $method) {
        $argsKey = $method->getName() . self::ARGS_SUFFIX;
        if ($this->daoBeanDesc->hasConstant($argsKey)) {
            $argNames = $this->daoBeanDesc->getConstant($argsKey);
            return S2Dao_ArrayUtil::spacetrim(explode(',', $argNames));
        } else {
            $params = array();
            foreach($method->getParameters() as $param){
                if(!$param->isDefaultValueAvailable()){
                    $params[] = $param->getName();
                } else {
                    $defparam = $param->getDefaultValue();
                    if(is_null($defparam)){
                        return array();
                    } else {
                        $params[] = $param->getName();
                    }
                }
            }
            return $params;
        }
    }

    public function getQuery(ReflectionMethod $method) {
        $key = $method->getName() . self::QUERY_SUFFIX;
        if ($this->daoBeanDesc->hasConstant($key)) {
            return $this->daoBeanDesc->getConstant($key);
        } else {
            return null;
        }
    }

    public function getBeanClass() {
        $beanField = $this->daoBeanDesc->getConstant(self::BEAN);
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
        if ($this->daoBeanDesc->hasConstant($key)) {
            return $this->daoBeanDesc->getConstant($key);
        }
        $key = $method->getName() . self::SQL_SUFFIX;
        if ($this->daoBeanDesc->hasConstant($key)) {
            return $this->daoBeanDesc->getConstant($key);
        }
        return null;
    }

    private function getProps(ReflectionMethod $method, $constName){
        if ($this->daoBeanDesc->hasConstant($constName)) {
            $s = $this->daoBeanDesc->getConstant($constName);
            return S2Dao_ArrayUtil::spacetrim(explode(',', $s));
        }
        return null;
    }
}

?>