<?php

/**
 * @author nowel
 */
class S2Dao_InsertBatchAutoHandler extends S2Dao_AbstractBatchAutoHandler {

    public function __cunstruct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory,
                                S2Dao_BeanMetaData $beanMetaData,
                                $propertyTypes) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData, $propertyTypes);
    }

    protected function setupBindVariables($bean) {
        $this->setupInsertBindVariables($bean);
    }
}
?>
