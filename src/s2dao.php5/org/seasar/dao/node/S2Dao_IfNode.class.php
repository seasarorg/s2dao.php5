<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id: $
//
/**
 * @author nowel
 * @package org.seasar.s2dao.node
 */
class S2Dao_IfNode extends S2Dao_ContainerNode {

    private $expression = '';
    private $parsedExpression = null;
    private $elseNode = null;

    public function __construct($expression) {
        $this->expression = $expression;
        $this->parsedExpression = quotemeta($expression);
        $this->parseExpByManual();
    }
    
    private function parseExpByManual(){
        $exp = $this->parsedExpression;
        $exp = str_replace('\.', '.', $exp);
        $this->parsedExpression = $exp;
    }

    public function getExpression() {
        return $this->expression;
    }

    public function getElseNode() {
        return $this->elseNode;
    }

    public function setElseNode(S2Dao_ElseNode $elseNode) {
        $this->elseNode = $elseNode;
    }

    public function accept(S2Dao_CommandContext $ctx) {
        $expression = preg_replace('/^(\w+)(\s+.*)/i',
                        '$ctx->getArg("\1")' . '\2', $this->parsedExpression);
        $expression = S2Container_EvalUtil::getExpression($expression);
        $result = eval($expression);

        if (is_bool($result)) {
            if ($result) {
                parent::accept($ctx);
                $ctx->setEnabled(true);
            } else if ($this->elseNode != null) {
                $this->elseNode->accept($ctx);
                $ctx->setEnabled(true);
            }
        } else {
            throw new S2Dao_IllegalBoolExpressionRuntimeException($this->expression);
        }
    }
}
?>
