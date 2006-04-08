<?php

/**
 * @author nowel
 */
class S2Dao_UpdateDynamicCommand extends S2Dao_AbstractDynamicCommand {

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory) {
        parent::__construct($dataSource, $statementFactory);
    }

    public function execute($args) {
        $ctx = $this->apply($args);
        $updateHandler = new S2Dao_BasicUpdateHandler(
                                $this->getDataSource(),
                                $ctx->getSql(),
                                $this->getStatementFactory());

        return (int)$updateHandler->execute($ctx->getBindVariables(),
                                       $ctx->getBindVariableTypes());
    }
}
?>
