<?php
  
/**
 * SelectDynamicCommandが生成したSQLにlimit句をつけるラッパクラス
 * @author yonekawa
 */
class S2Dao_SelectDynamicCommandLimitOffsetWrapper extends S2Dao_AbstractDynamicCommand
{
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
        return $sql . " LIMIT ?,?";
    }

    private function createBindVariables($bindVariables, $args)
    {
        $condition = $args[0];
        array_push($bindVariables, $condition->getOffset());
        array_push($bindVariables, $condition->getLimit());
        
        return $bindVariables;
    }

    private function createBindVariableTypes($bindVariableTypes, $args)
    {
        return $bindVariableTypes;
    }
}
?>