<?php

/**
 * @author Yusuke Hata
 */
class EmbeddedValueNode extends AbstractNode {

    private $expression_ = "";
    private $baseName_ = "";
    private $propertyName_ = "";

    public function __construct($expression) {
        $this->expression_ = $expression;
        $array = explode(".",$expression);
        $this->baseName_ = $array[0];
        if (1 < count($array)) {
            $this->propertyName_ = $array[1];
        }
    }

    public function getExpression() {
        return $this->expression_;
    }

    public function accept(CommandContext $ctx) {
        $value = $ctx->getArg($this->baseName_);
        $clazz = $ctx->getArgType($this->baseName_);

        if ($this->propertyName_ != null) {
            $beanDesc = BeanDescFactory::getBeanDesc($clazz);
            $pd = $beanDesc->getPropertyDesc($this->propertyName_);
            $value = $pd->getValue($value);
            $clazz = $pd->getPropertyType();
        }
        if ($value != null) {
            $ctx->addSql((string)$value);
        }
    }
}
?>
