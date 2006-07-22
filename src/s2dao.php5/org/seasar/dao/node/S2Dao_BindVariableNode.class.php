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
class S2Dao_BindVariableNode extends S2Dao_AbstractNode {

    private $expression = '';
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
        
        $c = count($this->names);
        for($pos = 1; $pos < $c; $pos++){
            if(gettype(new stdClass) == $clazz || is_object($clazz)){
                if($value === null){
                    continue;
                }
                $refClass = new ReflectionClass($value);
                $beanDesc = S2Container_BeanDescFactory::getBeanDesc($refClass);
                $pd = $beanDesc->getPropertyDesc($this->names[$pos]);
                if (!is_object($value)) {
                    break;
                }
                $value = $pd->getValue($value);
                $clazz = $pd->getPropertyType();
            }
        }

        if($this->isNull($clazz)){
            if($this->isNull($value)){
                $ctx->addSql('?', null, gettype(null));
            } else {
                $ctx->addSql('?', $value, gettype($value));
            }
        } else {
            $ctx->addSql('?', $value, $clazz);
        }
    }
    
    private function isNull($clazz = null){
        return $clazz === null || $clazz == gettype(null);
    }
}
?>
