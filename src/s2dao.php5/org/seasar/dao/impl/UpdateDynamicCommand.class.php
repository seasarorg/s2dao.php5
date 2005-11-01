<?php

/**
 * @author nowel
 */
class UpdateDynamicCommand extends AbstractDynamicCommand {

    public function __construct(DataSource $dataSource, StatementFactory $statementFactory) {
        parent::__construct($dataSource, $statementFactory);
    }

    public function execute($args) {
        $ctx = $this->apply($args);
        $updateHandler = new BasicUpdateHandler(
                                $this->getDataSource(),
                                $ctx->getSql(),
                                $this->getStatementFactory());
        return $updateHandler->execute($ctx->getBindVariables(), $ctx->getBindVariableTypes());
    }
}
?>
