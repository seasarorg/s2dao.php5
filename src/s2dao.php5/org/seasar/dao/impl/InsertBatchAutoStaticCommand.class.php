<?php

/**
 * @author Yusuke Hata
 */
class InsertBatchAutoStaticCommand extends AbstractBatchAutoStaticCommand {

    public function __construct(DataSource $dataSource,
                                //StatementFactory $statementFactory,
                                $statementFactory,
                                BeanMetaData $beanMetaData,
                                $propertyNames) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData, $propertyNames);
    }

    protected function createAutoHandler() {
        return new InsertBatchAutoHandler($this->getDataSource(),
                                          $this->getStatementFactory(),
                                          $this->getBeanMetaData(),
                                          $this->getPropertyTypes());
    }

    protected function setupSql() {
        $this->setupInsertSql();
    }

    protected function setupPropertyTypes($propertyNames) {
        $this->setupInsertPropertyTypes($propertyNames);
    }
}
?>
