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
class S2Dao_SelectDynamicCommand extends S2Dao_AbstractDynamicCommand {

    private $resultSetHandler = null;
    private $resultSetFactory = null;

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null,
                                S2Dao_ResultSetHandler $resultSetHandler,
                                S2Dao_ResultSetFactory $resultSetFactory = null){

        parent::__construct($dataSource, $statementFactory);
        $this->resultSetHandler = $resultSetHandler;
        $this->resultSetFactory = $resultSetFactory;
    }

    public function getResultSetHandler() {
        return $this->resultSetHandler;
    }
    
    public function getResultSetFactory(){
        return $this->resultSetFactory;
    }

    public function execute($args) {
        $ctx = $this->apply($args);
        $selectHandler = new S2Dao_BasicSelectHandler(
                                $this->getDataSource(),
                                $ctx->getSql(),
                                $this->resultSetHandler,
                                $this->getStatementFactory(),
                                $this->resultSetFactory);

        return $selectHandler->execute($ctx->getBindVariables(),
                                       $ctx->getBindVariableTypes());
    }
}
?>
