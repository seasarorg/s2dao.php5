<?php
  
/**
 * SelectDynamicCommandをラップしてlimit句をつけるラッパクラス
 * @author yonekawa
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
        $connection = $this->selectDynamicCommand_->getConnection();
        $dbms = S2Dao_DbmsManager::getDbms($connection);

        if ($dbms instanceof S2Dao_PostgreSQL) { 
            $this->dsn = self::DSN_PGSQL;
        }
        else if ($dbms instanceof S2Dao_MySQL) {
            $this->dsn = self::DSN_MYSQL;
        }
        
        return $sql . " " . $dbms->getLimitOffsetSql();
    }

    private function createBindVariables($bindVariables, $args)
    {
        if (!($this->dsn === self::DSN_OTHER)) {
            $condition = $args[0];
            array_push($bindVariables, $condition->getOffset());
            array_push($bindVariables, $condition->getLimit());
        }
        return $bindVariables;
    }

    private function createBindVariableTypes($bindVariableTypes, $args)
    {
        if (!($this->dsn === self::DSN_OTHER)) {
            array_push($bindVariableTypes, S2Dao_PHPType::Integer);
            array_push($bindVariableTypes, S2Dao_PHPType::Integer);
        }
        print_r($bindVariableTypes);
        return $bindVariableTypes;
    }
}
?>