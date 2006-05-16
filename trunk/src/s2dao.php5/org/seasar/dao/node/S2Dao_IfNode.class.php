<?php

/**
 * @author nowel
 */
class S2Dao_IfNode extends S2Dao_ContainerNode {

    private $expression_ = '';
    private $parsedExpression_ = null;
    private $elseNode_ = null;

    public function __construct($expression) {
        $this->expression_ = $expression;
        $this->parsedExpression_ = quotemeta($expression);
        $this->parseExpByManual();
    }
    
    private function parseExpByManual(){
        $exp = $this->parsedExpression_;
        $exp = str_replace('\.', '.', $exp);
        $this->parsedExpression_ = $exp;
    }

    public function getExpression() {
        return $this->expression_;
    }

    public function getElseNode() {
        return $this->elseNode_;
    }

    public function setElseNode(S2Dao_ElseNode $elseNode) {
        $this->elseNode_ = $elseNode;
    }

    public function accept(S2Dao_CommandContext $ctx) {
        $expression = preg_replace('/^(\w+)(\s+.*)/i',
                        '$ctx->getArg("\1")' . '\2', $this->parsedExpression_);
        $expression = S2Container_EvalUtil::getExpression($expression);
        $result = eval($expression);

        if (is_bool($result)) {
            if ($result) {
                parent::accept($ctx);
                $ctx->setEnabled(true);
            } else if ($this->elseNode_ != null) {
                $this->elseNode_->accept($ctx);
                $ctx->setEnabled(true);
            }
        } else {
            throw new S2Dao_IllegalBoolExpressionRuntimeException($this->expression_);
        }
    }
}
?>
