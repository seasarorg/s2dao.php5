<?php

abstract class S2Dao_AbstractBeanAnnotationReader {
    
    protected $comment = null;
    protected $constant = null;

    public function __construct($beanClass) {
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($beanClass);
        $this->comment = new S2Dao_BeanCommentAnnotationReader($beanDesc);
        $this->constant = new S2Dao_BeanConstantAnnotationReader($beanDesc);
    }
    
    public function __call($name, $param){
        if(S2Dao::USE_COMMENT === true){
            if(method_exists($this->comment, $name)){
                return $this->call($this->comment, $name, $param);
            }
        }

        if(method_exists($this->constant, $name)){
            return $this->call($this->constant, $name, $param);
        }
    }
    
    protected function call(S2Dao_BeanAnnotationReader $claz,
                            $methodName,
                            $parameter){
        return call_user_func_array(
                    array($claz, $methodName),
                    $parameter);
    }
}

?>