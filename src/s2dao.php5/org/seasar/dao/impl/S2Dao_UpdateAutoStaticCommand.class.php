<?php

/**
 * @author nowel
 */
class S2Dao_UpdateAutoStaticCommand extends S2Dao_AbstractAutoStaticCommand {

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null,
                                S2Dao_BeanMetaData $beanMetaData,
                                $propertyNames) {
        
        parent::__construct($dataSource, $statementFactory,
                            $beanMetaData, $propertyNames);
    }

    protected function createAutoHandler() {
        return new S2Dao_UpdateAutoHandler(
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
