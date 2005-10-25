<?php

/**
 * @author nowel
 */
class UpdateBatchAutoHandler extends AbstractBatchAutoHandler {

    public function __construct(DataSource $dataSource,
                                //StatementFactory $statementFactory,
                                $statementFactory,
                                BeanMetaData $beanMetaData,
                                $propertyTypes) {

        parent::__construct($dataSource, $statementFactory,
                            $beanMetaData, $propertyTypes);
    }

    protected function setupBindVariables($bean) {
        $this->setupUpdateBindVariables($bean);
    }
}
?>
