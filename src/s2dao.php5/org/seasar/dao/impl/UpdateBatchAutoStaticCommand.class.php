<?php

/**
 * @author nowel
 */
class UpdateBatchAutoStaticCommand extends AbstractBatchAutoStaticCommand {

    public function __construct(DataSource $dataSource,
                                //StatementFactory $statementFactory,
                                $statementFactory,
                                BeanMetaData $beanMetaData,
                                $propertyNames) {

        parent::__construct($dataSource, $statementFactory,
                            $beanMetaData, $propertyNames);
    }

    protected function createAutoHandler() {
        return new UpdateBatchAutoHandler(
                        $this->getDataSource(),
                        $this->getStatementFactory(),
                        $this->getBeanMetaData(),
                        $this->getPropertyTypes());
    }

    protected function setupSql() {
        $this->setupUpdateSql();
    }

    protected function setupPropertyTypes($propertyNames) {
        $this->setupUpdatePropertyTypes($propertyNames);
    }
}
?>
