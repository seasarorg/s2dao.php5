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
// $Id$
//
/**
 * @author nowel
 */
class S2Dao_ParenBindVariableNode extends S2Dao_AbstractNode {

    private $expression_ = '';
    private $parsedExpression_ = null;

    public function __construct($expression) {
        $this->expression_ = $expression;
        $this->parsedExpression_ = quotemeta($expression);
    }

    public function getExpression() {
        return $this->expression_;
    }

    public function accept(S2Dao_CommandContext $ctx) {
        $expression = preg_replace('/^(\w+)(\s+.*)/i',
                        '\$ctx->getArg("\1")' . '\2', $this->parsedExpression_);
        $expression = EvalUtil::getExpression($expression);
        $result = eval($expression);
        
        if ($result instanceof S2Dao_ArrayList) {
            $this->bindArray($ctx, $value->toArray());
        } else if ($result == null) {
            return;
        } else if (is_array($result)) {
            $this->bindArray($ctx, $result);
        } else {
            $ctx->addSql('?', $result, get_class($result));
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
            if ($o !== null) {
                $clazz = get_class($o);
                /*
                if(is_object($o)){
                    $clazz = get_class($o);
                } else {
                    $clazz = gettype($o);
                }
                */
            }
        }
        $ctx->addSql('(');
        $ctx->addSql('?', $array[0], $clazz);
        for ($i = 1; $i < $length; ++$i) {
            $ctx->addSql(', ?', $array[$i], $clazz);
        }
        $ctx->addSql(')');
    }
}
?>