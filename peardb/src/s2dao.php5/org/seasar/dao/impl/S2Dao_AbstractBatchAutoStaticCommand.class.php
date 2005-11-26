<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractBatchAutoStaticCommand extends S2Dao_AbstractAutoStaticCommand {

    public function __construct(S2Container_DataSource $dataSource,
                                //S2Dao_StatementFactory $statementFactory,
                                $statementFactory,
                                S2Dao_BeanMetaData $beanMetaData,
                                $propertyNames) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData, $propertyNames);
    }

    public function execute($args) {
        $handler = $this->createAutoHandler();
        $handler->setSql($this->getSql());
        $updatedRows = $handler->execute($args);
        return (int)$updatedRows;
    }
}
?>
