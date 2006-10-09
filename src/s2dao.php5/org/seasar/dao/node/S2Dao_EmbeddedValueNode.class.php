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
 */
class S2Dao_EmbeddedValueNode extends S2Dao_AbstractNode {

    private $expression_ = '';
    private $baseName_ = '';
    private $propertyName_ = '';

    public function __construct($expression) {
        $this->expression_ = $expression;
        $array = explode('.',$expression);
        $this->baseName_ = $array[0];
        if (1 < count($array)) {
            $this->propertyName_ = $array[1];
        }
    }

    public function getExpression() {
        return $this->expression_;
    }

    public function accept(S2Dao_CommandContext $ctx) {
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
