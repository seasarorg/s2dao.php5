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
 * SelectDynamicCommand�㉃b�v����limit�����郉�b�p�N���X
 * @author yonekawa
 * @author nowel
 */
class S2Dao_SelectDynamicCommandLimitOffsetWrapper extends S2Dao_AbstractDynamicCommand
{
    const DSN_MYSQL = 0;
    const DSN_PGSQL = 1;
    const DSN_OTHER = 9;
    
    private $dsn = self::DSN_OTHER;
    private $selectDynamicCommand_ = null;

    public function __construct(S2Dao_SelectDynamicCommand $selectDynamicCommand)
    {
        $this->selectDynamicCommand_ = $selectDynamicCommand;
    }

    public function execute($args) 
    {
        $ctx = $this->selectDynamicCommand_->apply($args);
        $sqlWithLimit = $this->createSqlWithLimit($ctx->getSql());
        
        $selectHandler = new S2Dao_BasicSelectHandler(
                                $this->selectDynamicCommand_->getDataSource(),
                                $sqlWithLimit,
                                $this->selectDynamicCommand_->getResultSetHandler(),
                                $this->selectDynamicCommand_->getStatementFactory(),
                                $this->selectDynamicCommand_->getResultSetFactory());
        
        $bindVariables = $this->createBindVariables($ctx->getBindVariables(), $args);
        $bindVariableTypes = $this->createBindVariableTypes($ctx->getBindVariableTypes(),$args);

        return $selectHandler->execute($bindVariables,$bindVariableTypes);
    }

    private function createSqlWithLimit($sql)
    {
        $connection = $this->selectDynamicCommand_->getDataSource()->getConnection();
        $dbms = S2Dao_DbmsManager::getDbms($connection);

        if ($dbms instanceof S2Dao_PostgreSQL) { 
            $this->dsn = self::DSN_PGSQL;
        }
        else if ($dbms instanceof S2Dao_MySQL) {
            $this->dsn = self::DSN_MYSQL;
        }
        
        return $sql . ' ' . $dbms->getLimitOffsetSql();
    }

    private function createBindVariables($bindVariables, $args)
    {
        if (!($this->dsn === self::DSN_OTHER)) {
            $condition = $args[0];
            $bindVariables[] = $condition->getOffset();
            $bindVariables[] = $condition->getLimit();
        }
        return $bindVariables;
    }

    private function createBindVariableTypes($bindVariableTypes, $args)
    {
        if (!($this->dsn === self::DSN_OTHER)) {
            $bindVariableTypes[] = S2Dao_PHPType::Integer;
            $bindVariableTypes[] = S2Dao_PHPType::Integer;
        }
        print_r($bindVariableTypes);
        return $bindVariableTypes;
    }
}
?>