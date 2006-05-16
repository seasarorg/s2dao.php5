<?php

/**
 * @author nowel
 */
class S2Dao_IllegalBoolExpressionRuntimeException extends S2Container_S2RuntimeException {

    private $expression;
    
    public function __construct($expression) {
        parent::__construct('EDAO0003', array($expression));
        $this->expression = $expression;
    }
    
    public function getExpression() {
        return $this->expression;
    }
}
?>
