<?php

/**
 * @author nowel
 */
class S2Dao_ParenBindVariableNode extends S2Dao_AbstractNode {

    private $expression_ = "";
    private $parsedExpression_ = null;

    public function __construct($expression) {
        $this->expression_ = $expression;
        $this->parsedExpression_ = quotemeta($expression);
    }

    public function getExpression() {
        return $this->expression_;
    }

    public function accept(S2Dao_CommandContext $ctx) {
        $expression = preg_replace("/^(\w+)(\s+.*)/i",
                        "\$ctx->getArg(\"\\1\")" . "\\2", $this->parsedExpression_);
        $expression = EvalUtil::getExpression($expression);
        $result = eval($expression);
        
        if ($value instanceof S2Dao_ArrayList) {
            $this->bindArray($ctx, $value->toArray());
        } else if ($value == null) {
            return;
        } else if (is_array($value)) {
            $this->bindArray($ctx, $value);
        } else {
            $ctx->addSql("?", $value, get_class($value));
        }
    }

    private function bindArray(S2Dao_CommandContext $ctx, $array) {
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
