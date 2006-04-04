<?php

/**
 * @author nowel
 */
class S2Dao_DaoCommentAnnotationReader implements S2Dao_DaoAnnotationReader {

    const SQL_SUFFIX = S2Dao_DaoConstantAnnotationReader::SQL_SUFFIX;
    const NO_PERSISTENT_PROPS = 'NO_PERSISTENT_PROPS';
    const PERSISTENT_PROPS = 'PERSISTENT_PROPS';
    const SELECT_ARRAY_NAME = '/@return\s*array/i';
    const SELECT_LIST_NAME = '/@return\s*list/i';
    const Anno = 'S2Dao_DaoAnnotation';

    protected $beanClass;
    
    public function __construct(S2Container_BeanDesc $beanDesc) {
        $this->beanClass = $beanDesc->getBeanClass();
    }
    
    public function getBeanClass() {
        $anno = S2Container_Annotations::getAnnotation(self::Anno, $this->beanClass);
        return new ReflectionClass(new $anno->BEAN);
    }

    public function getArgNames(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation($method);
        if (isset($anno->ARGS)) {
            return S2Dao_ArrayUtil::spacetrim(explode(',', $anno->ARGS));
        } else {
            $params = array();
            foreach($method->getParameters() as $param){
                $params[] = $param->getName();
                /*
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
                */
            }
            return $params;
        }
    }

    public function getNoPersistentProps(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation($method);
        return $this->getProps($anno, self::NO_PERSISTENT_PROPS);
    }

    public function getPersistentProps(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation($method);
        return $this->getProps($anno, self::PERSISTENT_PROPS);
    }

    public function getQuery(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation($method);
        return $anno->QUERY;
    }

    public function getSQL(ReflectionMethod $method, $dbmsSuffix) {
        $key = $dbmsSuffix . self::SQL_SUFFIX;
        $anno = $this->getMethodAnnotation($method);
        if(isset($anno->$key)){
            return $anno->$key;
        }
        return $anno->SQL;
    }
    
    public function isSelectList(ReflectionMethod $method){
        $comment = $this->getMethodComment($method);
        return preg_match(self::SELECT_LIST_NAME, $comment);
    }
    
    public function isSelectArray(ReflectionMethod $method){
        $comment = $this->getMethodComment($method);
        return preg_match(self::SELECT_ARRAY_NAME, $comment);
    }

    private function getProps(S2Dao_DaoAnnotation $anno = null, $annoName){
        if($anno == null || !isset($anno->$annoName)){
            return null;
        }
        return S2Dao_ArrayUtil::spacetrim(explode(',', $anno->$annoName));
    }
    
    private function getMethodAnnotation(ReflectionMethod $method){
        if(S2Container_Annotations::isAnnotationPresent(self::Anno,
                                                      $this->beanClass,
                                                      $method->getName())){
            return S2Container_Annotations::getAnnotation(self::Anno,
                                                          $this->beanClass,
                                                          $method->getName());
        }
        return null;
    }
    
    private function getMethodComment(ReflectionMethod $method){
        $method = $this->beanClass->getMethod($method->getName());
        return $method->getDocComment();

    }
}

?>