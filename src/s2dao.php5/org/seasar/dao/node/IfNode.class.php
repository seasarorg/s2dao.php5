<?php

/**
 * @author nowel
 */
class IfNode extends ContainerNode {

    private $expression_ = "";
    private $parsedExpression_ = null;
    private $elseNode_ = null;

    public function __construct($expression) {
        $this->expression_ = $expression;
        $this->parsedExpression_ = quotemeta($expression);
    }

    public function getExpression() {
        return $this->expression_;
    }

    public function getElseNode() {
        return $this->elseNode_;
    }

    public function setElseNode(ElseNode $elseNode) {
        $this->elseNode_ = $elseNode;
    }

    public function accept(CommandContext $ctx) {
        //preg_match("/^(\w+)\s+.*/i", $this->parsedExpression_, $match);
        $expression = preg_replace("/^(\w+)(\s+.*)/i",
                        "\$ctx->getArg(\"\\1\")" . "\\2", $this->parsedExpression_);
        $expression = EvalUtil::getExpression($expression);
        $result = eval($expression);

        if (is_bool($result)) {
            if ( $result == true ) {
                parent::accept($ctx);
                $ctx->setEnabled(true);
            } else if ($this->elseNode_ != null) {
                $this->elseNode_->accept($ctx);
                $ctx->setEnabled(true);
            }
        } else {
            throw new IllegalBoolExpressionRuntimeException($this->expression_);
        }
    }
}
?>
