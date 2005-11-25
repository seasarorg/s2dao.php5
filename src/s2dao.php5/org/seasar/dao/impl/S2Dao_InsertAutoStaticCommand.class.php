<?php

/**
 * @author nowel
 */
class S2Dao_InsertAutoStaticCommand extends S2Dao_AbstractAutoStaticCommand {

    public function __construct(S2Container_DataSource $dataSource,
                                //S2Dao_StatementFactory $statementFactory,
                                $statementFactory,
                                S2Dao_BeanMetaData $beanMetaData,
                                $propertyNames) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData, $propertyNames);
    }

    protected function createAutoHandler() {
        return new S2Dao_InsertAutoHandler($this->getDataSource(),
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