<?php

/**
 * @author nowel
 */
class InsertBatchAutoHandler extends AbstractBatchAutoHandler {

    public function __cunstruct(DataSource $dataSource,
                                StatementFactory $statementFactory,
                                BeanMetaData $beanMetaData,
                                $propertyTypes) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData, $propertyTypes);
    }

    protected function setupBindVariables($bean) {
        $this->setupInsertBindVariables($bean);
    }
}
?>
