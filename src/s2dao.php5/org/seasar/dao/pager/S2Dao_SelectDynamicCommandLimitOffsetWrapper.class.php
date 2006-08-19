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
// | Authors: yonekawa                                                    |
// +----------------------------------------------------------------------+
// $Id$
//  
/**
 * SelectDynamicCommandをLimit,Offset句を使って実行する
 * @author yonekawa
 * @author nowel
 */
class S2Dao_SelectDynamicCommandLimitOffsetWrapper extends S2Dao_AbstractDynamicCommand
{
    private $selectDynamicCommand_ = null;
    private $bindVariables = array();
    private $bindVariableTypes = array();

    public function __construct(S2Dao_SelectDynamicCommand $selectDynamicCommand)
    {
        $this->selectDynamicCommand_ = $selectDynamicCommand;
    }

    public function execute($args) 
    {
        $condition = $args[0];
        $ctx = $this->selectDynamicCommand_->apply($args);
        
        $variables = $ctx->getBindVariables();
        $variableTypes = $ctx->getBindVariableTypes();

        $this->bindVariables = $variables;
        $this->bindVariableTypes = $variableTypes;
        $sqlWithLimit = $this->createSqlWithLimit($ctx->getSql(), $condition);
        
        $selectHandler = new S2Dao_BasicSelectHandler(
                                $this->selectDynamicCommand_->getDataSource(),
                                $sqlWithLimit,
                                $this->selectDynamicCommand_->getResultSetHandler(),
                                $this->selectDynamicCommand_->getStatementFactory(),
                                $this->selectDynamicCommand_->getResultSetFactory());

        $condition->setCount($this->getCount($ctx->getSql(),$variables, $variableTypes));
        return $selectHandler->execute($this->bindVariables, $this->bindVariableTypes);
    }

    private function createSqlWithLimit($sql, $condition)
    {
        $connection = $this->selectDynamicCommand_->getDataSource()->getConnection();
        $dbms = S2DaoDbmsManager::getDbms($connection);

        if (!$dbms->usableLimitOffsetQuery()) {
            return $sql;
        }
        $sql = $sql . ' ' . $dbms->getLimitOffsetSql();
        $this->bindVariables[] = $condition->getOffset();
        $this->bindVariableTypes[] = S2Dao_PHPType::Integer;
        $this->bindVariables[] = $condition->getLimit();
        $this->bindVariableTypes[] = S2Dao_PHPType::Integer;
        
        return $sql;
    }

    private function getCount($baseSql, $variables, $variableTypes)
    {
        $getCountSql = 'SELECT COUNT(*) FROM (' . $baseSql . ') AS total';

        $selectHandler = new S2Dao_BasicSelectHandler(
                                $this->selectDynamicCommand_->getDataSource(),
                                $getCountSql,
                                $this->selectDynamicCommand_->getResultSetHandler(),
                                $this->selectDynamicCommand_->getStatementFactory(),
                                $this->selectDynamicCommand_->getResultSetFactory());

        return $selectHandler->execute($variables, $variableTypes);
    }
}
?>