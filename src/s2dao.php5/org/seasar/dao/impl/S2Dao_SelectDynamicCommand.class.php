<?php

/**
 * @author nowel
 */
class S2Dao_SelectDynamicCommand extends S2Dao_AbstractDynamicCommand {

    private $resultSetHandler_ = null;
    private $resultSetFactory_ = null;

    public function __construct(S2Container_DataSource $dataSource,
                                //S2Dao_StatementFactory $statementFactory,
                                $statementFactory,
                                S2Container_ResultSetHandler $resultSetHandler,
                                $resultSetFactory){
                                //S2Dao_ResultSetFactory $resultSetFactory

        parent::__construct($dataSource, $statementFactory);
        $this->resultSetHandler_ = $resultSetHandler;
        $this->resultSetFactory_ = $resultSetFactory;
    }

    public function getResultSetHandler() {
        return $this->resultSetHandler_;
    }

    public function execute($args) {
        $ctx = $this->apply($args);
        $selectHandler = new S2Dao_BasicSelectHandler(
                                $this->getDataSource(),
                                $ctx->getSql(),
                                $this->resultSetHandler_,
                                $this->getStatementFactory(),
                                $this->resultSetFactory_);
                                
        return $selectHandler->execute($ctx->getBindVariables(),
                                       $ctx->getBindVariableTypes());
    }
}
?>
