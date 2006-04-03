<?php

abstract class S2Dao_AbstractDaoAnnotationReader {
    
    protected $comment = null;
    protected $constant = null;
    
    protected function __construct(S2Container_BeanDesc $daoBeanDesc) {
        $this->comment = new S2Dao_DaoCommentAnnotationReader($daoBeanDesc);
        $this->constant = new S2Dao_DaoConstantAnnotationReader($daoBeanDesc);
    }
    
    public function __call($name, $param){
        if(S2Dao::USE_COMMENT){
            if(method_exists($this->comment, $name)){
                return $this->call($this->comment, $name, $param);
            }
        }

        if(method_exists($this->constant, $name)){
            return $this->call($this->constant, $name, $param);
        }
    }
    
    protected function call(S2Dao_DaoAnnotationReader $claz,
                            $methodName,
                            $parameter){
        return call_user_func_array(
                    array($claz, $methodName),
                    $parameter);
    }
}

?>