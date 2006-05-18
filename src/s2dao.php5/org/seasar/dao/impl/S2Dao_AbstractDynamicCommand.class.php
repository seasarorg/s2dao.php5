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
abstract class S2Dao_AbstractDynamicCommand extends S2Dao_AbstractSqlCommand {

    private $rootNode_;
    private $argNames_ = array();
    private $argTypes_ = array();

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null){
        parent::__construct($dataSource, $statementFactory);
    }

    public function setSql($sql) {
        parent::setSql($sql);
        $sqlparser = new S2Dao_SqlParserImpl($sql);
        $this->rootNode_ = $sqlparser->parse();
    }

    public function getArgNames() {
        return $this->argNames_;
    }

    public function setArgNames($argNames) {
        $this->argNames_ = $argNames;
    }

    public function getArgTypes() {
        return $this->argTypes_;
    }

    public function setArgTypes($argTypes) {
        $this->argTypes_ = $argTypes;
    }

    protected function apply($args) {
        $ctx = $this->createCommandContext($args);
        $this->rootNode_->accept($ctx);
        return $ctx;
    }

    protected function createCommandContext($args) {
        $ctx = new S2Dao_CommandContextImpl();
        if ($args != null) {
            $c = count($args);
            for ($i = 0; $i < $c; ++$i) {
                $argType = null;
                if ($args[$i] !== null){
                    if ($i < count($this->argTypes_)) {
                        $argType = $this->argTypes_[$i];
                    } else if ($args[$i] !== null) {
                        $argType = gettype($args[$i]);
                    }
                }
                if ($i < count($this->argNames_)) {
                    $ctx->addArg($this->argNames_[$i], $args[$i], $argType);
                } else {
                    $ctx->addArg('$' . ($i + 1), $args[$i], $argType);
                }
            }
        }
        return $ctx;
    }
}
?>
