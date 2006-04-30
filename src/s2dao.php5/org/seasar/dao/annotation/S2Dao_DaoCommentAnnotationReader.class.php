<?php

/**
 * @author nowel
 */
class S2Dao_DaoCommentAnnotationReader implements S2Dao_DaoAnnotationReader {

    const SQL_SUFFIX = S2Dao_DaoConstantAnnotationReader::SQL_SUFFIX;
    const SELECT_ARRAY_NAME = '/@return\s*array/i';
    const SELECT_LIST_NAME = '/@return\s*list/i';
    protected $beanClass;
    
    public function __construct(S2Container_BeanDesc $beanDesc) {
        $this->beanClass = $beanDesc->getBeanClass();
    }
    
    public function getBeanClass() {
        $anno = S2Container_Annotations::getAnnotation('Dao',
                                $this->beanClass->getName());
        return new ReflectionClass(new $anno->bean);
    }

    public function getArgNames(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('Arguments', $method);
        if (0 < count($anno->value)) {
            return S2Dao_ArrayUtil::spacetrim(explode(',', $anno->value));
        } else {
            $params = array();
            foreach($method->getParameters() as $param){
                $params[] = $param->getName();
            }
            return $params;
        }
    }

    public function getNoPersistentProps(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('NoPersistentProperty', $method);
        if($anno !== null){
            return S2Dao_ArrayUtil::spacetrim(explode(',', $anno->value));
        }
        return null;
    }

    public function getPersistentProps(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('PersistentProperty', $method);
        if($anno !== null){
            return S2Dao_ArrayUtil::spacetrim(explode(',', $anno->value));
        }
        return null;
    }

    public function getQuery(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('Query', $method);
        return $anno->value;
    }

    public function getSQL(ReflectionMethod $method, $dbmsSuffix) {
        $dbmsSuffix = preg_replace('/^(_)/', '', $dbmsSuffix);
        $anno = $this->getMethodAnnotation('Sql', $method);
        if($anno != null){
            if($anno->dbms == $dbmsSuffix){
                return $anno->value;
            }
        }
        return null;
    }
    
    public function getStoredProcedureName(ReflectionMethod $method) {
        $anno = $this->getMethodAnnotation('Procedure', $method);
        if($anno != null){
            return $anno->value;
        }
        return null;
    }
    
    public function getReturnType(ReflectionMethod $method){
        return null;
    }
    
    public function isSelectList(ReflectionMethod $method){
        $comment = $this->getMethodComment($method);
        return preg_match(self::SELECT_LIST_NAME, $comment);
    }
    
    public function isSelectArray(ReflectionMethod $method){
        $comment = $this->getMethodComment($method);
        return preg_match(self::SELECT_ARRAY_NAME, $comment);
    }
    
    private function getMethodAnnotation($annoType, ReflectionMethod $method){
        if(S2Container_Annotations::isAnnotationPresent($annoType,
                                                      $this->beanClass,
                                                      $method->getName())){
            return S2Container_Annotations::getAnnotation($annoType,
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