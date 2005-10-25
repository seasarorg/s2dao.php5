<?php

/**
 * @author nowel
 */
class SelectDynamicCommand extends AbstractDynamicCommand {

    private $resultSetHandler_ = null;
    private $resultSetFactory_ = null;

    public function __construct(DataSource $dataSource,
                                //StatementFactory $statementFactory,
                                $statementFactory,
                                ResultSetHandler $resultSetHandler,
                                $resultSetFactory){
                                //ResultSetFactory $resultSetFactory

        parent::__construct($dataSource, $statementFactory);
        $this->resultSetHandler_ = $resultSetHandler;
        $this->resultSetFactory_ = $resultSetFactory;
    }

    public function getResultSetHandler() {
        return $this->resultSetHandler_;
    }

    public function execute($args) {
        $ctx = $this->apply($args);
        $selectHandler = new BasicSelectHandler(
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
