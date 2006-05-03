<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractAnnotationReader {
    
    protected $comment = null;
    protected $constant = null;

    public function __construct(S2Container_BeanDesc $beandesc,
                                $commentAnnotationReader,
                                $constantAnnotationReader) {
        $this->comment = $commentAnnotationReader;
        $this->constant = $constantAnnotationReader;
    }
    
    public function __call($name, $param){
        if(defined('S2DAO_PHP5_USE_COMMENT')){
            if(S2DAO_PHP5_USE_COMMENT === true){
                $this->setCommentAnnotationHandler();
                if(method_exists($this->comment, $name)){
                    return $this->call($this->comment, $name, $param);
                }
            }
        }

        if(method_exists($this->constant, $name)){
            return $this->call($this->constant, $name, $param);
        }
        
        throw new Exception();
    }
    
    protected function call($claz,
                            $methodName,
                            $parameter){
        return call_user_func_array(
                    array($claz, $methodName),
                    $parameter);
    }
    
    private function setCommentAnnotationHandler(){
        if(class_exists('S2Container_AnnotationContainer')){
            if(strcasecmp(S2Container_AnnotationContainer::$DEFAULT_ANNOTATION_READER,
                      'S2DaoAnnotationReader') != 0){
               S2Container_AnnotationContainer::$DEFAULT_ANNOTATION_READER = 'S2DaoAnnotationReader';
            }
        }
    }

}

?>