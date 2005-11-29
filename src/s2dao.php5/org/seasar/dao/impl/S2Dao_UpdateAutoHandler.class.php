<?php

/**
 * @author nowel
 */
class S2Dao_UpdateAutoHandler extends S2Dao_AbstractAutoHandler {

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null,
                                S2Dao_BeanMetaData $beanMetaData,
                                $propertyTypes) {

        parent::__construct($dataSource, $statementFactory,
                            $beanMetaData, $propertyTypes);
    }

    protected function setupBindVariables($bean) {
        $this->setupUpdateBindVariables($bean);
    }

    protected function postUpdateBean($bean) {
        $this->updateVersionNoIfNeed($bean);
        $this->updateTimestampIfNeed($bean);
    }
}
?>
