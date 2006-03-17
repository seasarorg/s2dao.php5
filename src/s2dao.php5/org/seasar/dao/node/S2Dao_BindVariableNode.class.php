<?php

/**
 * @author nowel
 */
class S2Dao_BindVariableNode extends S2Dao_AbstractNode {

    private $expression = "";
    private $names = array();

    public function __construct($expression) {
        $this->expression = $expression;
        $this->names = explode('.', $expression);
    }

    public function getExpression() {
        return $this->expression;
    }

    public function accept(S2Dao_CommandContext $ctx) {
        $value = $ctx->getArg($this->names[0]);
        $clazz = $ctx->getArgType($this->names[0]);
        
        for($pos = 1; $pos < count($this->names); $pos++){
            if("object" == $clazz){
                $beanDesc =
                    S2Container_BeanDescFactory::getBeanDesc(new ReflectionClass($value));
                $pd = $beanDesc->getPropertyDesc($this->names[$pos]);
                if (!is_object($value)) {
                    break;
                }
                $value = $pd->getValue($value);
                $clazz = $pd->getPropertyType();
            }
        }

        if($value != null && $clazz != null){
            settype($value, $clazz);
        } else {
            //settype($value, 'null');
        }
        $ctx->addSql("?", $value, $clazz);
    }
}
?>
