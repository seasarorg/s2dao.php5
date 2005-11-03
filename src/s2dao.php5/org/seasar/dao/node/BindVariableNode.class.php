<?php

/**
 * @author nowel
 */
class BindVariableNode extends AbstractNode {

    private $expression_ = "";
    private $baseName_ = array();
    private $propertyName_ = null;
    private $names = array();

    public function __construct($expression) {
        $this->expression_ = $expression;
        
        $this->names_ = explode(".", $expression);
//        $this->baseName_ = $array[0];
//        if(count($array) > 1){
//            $this->propertyName_ = $array[1];
//        }
    }

    public function getExpression() {
        return $this->expression_;
    }

    public function accept(CommandContext $ctx) {
        $value = $ctx->getArg($this->names_[0]);
        $clazz = $ctx->getArgType($this->names_[0]);
        
//        for($pos = 1; $pos < count($this->names_); $pos++){
//            $beanDesc = BeanDescFactory::getBeanDesc($clazz);
//            $pd = $beanDesc->getPropertyDesc($this->names_[$pos]);
//            if ($value == null) {
//                break;
//            }
//            $value = $pd->getValue($value);
//            $clazz = $pd->getPropertyType();
//        }

        if($value != null && $clazz != null){
            settype($value, $clazz);
        } else {
            //settype($value, 'null');
        }
        $ctx->addSql("?", $value, $clazz);
    }
}
?>
