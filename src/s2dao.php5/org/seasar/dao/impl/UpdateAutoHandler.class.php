<?php

/**
 * @author Yusuke Hata  
 */
class UpdateAutoHandler extends AbstractAutoHandler {

    public function __construct(DataSource $dataSource,
                                StatementFactory $statementFactory,
                                BeanMetaData $beanMetaData,
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
