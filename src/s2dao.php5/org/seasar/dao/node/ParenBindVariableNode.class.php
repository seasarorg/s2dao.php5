<?php

/**
 * @author nowel
 */
class ParenBindVariableNode extends AbstractNode {

    private $expression_ = "";
    private $parsedExpression_ = null;

    public function __construct($expression) {
        $this->expression_ = $expression;
        $this->parsedExpression_ = OgnlUtil::parseExpression($expression);
    }

    public function getExpression() {
        return $this->expression_;
    }

    public function accept(CommandContext $ctx) {
        $value = OgnlUtil::getValue($this->parsedExpression_, $ctx);
        if ($value instanceof ArrayList) {
            $this->bindArray($ctx, $value->toArray());
        } else if ($value == null) {
            return;
        } else if (is_array($value)) {
            $this->bindArray(ctx, $value);
        } else {
            $ctx->addSql("?", $value, get_class($value));
        }
    }

    private function bindArray(CommandContext $ctx, $array) {
        $length = count($array);
        if ($length == 0) {
            return;
        }
        $clazz = null;
        for ($i = 0; $i < $length; ++$i) {
            $o = $array[$i];
            if ($o != null) {
                $clazz = get_class($o);
            }
        }
        $ctx->addSql("(");
        $ctx->addSql("?", $array[0], $clazz);
        for ($i = 1; $i < $length; ++$i) {
            $ctx->addSql(", ?", $array[$i], $clazz);
        }
        $ctx->addSql(")");
    }
}
?>
