<?php

/**
 * @author Yusuke Hata
 */
class DeleteAutoStaticCommand extends AbstractAutoStaticCommand {

    public function __construct(DataSource $dataSource,
                                //StatementFactory $statementFactory,
                                $statementFactory,
                                BeanMetaData $beanMetaData,
                                $propertyNames) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData, $propertyNames);
    }

    protected function createAutoHandler() {
        return new DeleteAutoHandler($this->getDataSource(),
                                     $this->getStatementFactory(),
                                     $this->getBeanMetaData(),
                                     $this->getPropertyTypes());
    }
    
    protected function setupSql() {
        $this->setupDeleteSql();
    }

    protected function setupPropertyTypes($propertyNames) {
        $this->setupDeletePropertyTypes($propertyNames);
    }
}
?>
