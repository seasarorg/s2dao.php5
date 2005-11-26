<?php

/**
 * @author nowel
 */
class S2Dao_DeleteAutoHandler extends S2Dao_AbstractAutoHandler {

    public function __construct(S2Container_DataSource $dataSource,
                                $statementFactory,
                                S2Dao_BeanMetaData $beanMetaData,
                                $propertyTypes) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData, $propertyTypes);
    }

    protected function setupBindVariables($bean) {
        $this->setupDeleteBindVariables($bean);
    }
}
?>
