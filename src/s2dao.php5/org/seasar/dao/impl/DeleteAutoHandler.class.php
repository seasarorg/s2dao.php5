<?php

/**
 * @author Yusuke Hata
 */
class DeleteAutoHandler extends AbstractAutoHandler {

    public function __construct(DataSource $dataSource,
                                StatementFactory $statementFactory,
                                BeanMetaData $beanMetaData,
                                $propertyTypes) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData, $propertyTypes);
    }

    protected function setupBindVariables($bean) {
        $this->setupDeleteBindVariables($bean);
    }
}
?>
