<?php

/**
 * @author nowel
 */
abstract class AbstractBatchAutoStaticCommand extends AbstractAutoStaticCommand {

    public function __construct(DataSource $dataSource,
                                //StatementFactory $statementFactory,
                                $statementFactory,
                                BeanMetaData $beanMetaData,
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
