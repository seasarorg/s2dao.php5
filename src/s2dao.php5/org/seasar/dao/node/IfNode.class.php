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
        $this->parsedExpression_ = EvalUtil::getExpression($expression);
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
        //$result = getValue($this->parsedExpression_, $ctx);
        $result = eval($this->parsedExpression_);
        var_dump($ctx);

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
