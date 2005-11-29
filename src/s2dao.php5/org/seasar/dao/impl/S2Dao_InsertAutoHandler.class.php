<?php

/**
 * @author nowel
 */
class S2Dao_InsertAutoHandler extends S2Dao_AbstractAutoHandler {

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null,
                                S2Dao_BeanMetaData $beanMetaData,
                                $propertyTypes) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData, $propertyTypes);
    }

    protected function setupBindVariables($bean) {
        $this->setupInsertBindVariables($bean);
    }

    protected function preUpdateBean($bean) {
        $generator = $this->getBeanMetaData()->getIdentifierGenerator();
        if ($generator->isSelfGenerate()) {
            $generator->setIdentifier($bean, $this->getDataSource());
        }
    }

    protected function postUpdateBean($bean) {
        $generator = $this->getBeanMetaData()->getIdentifierGenerator();
        if (!$generator->isSelfGenerate()) {
            $generator->setIdentifier($bean, $this->getDataSource());
        }
        $this->updateVersionNoIfNeed($bean);
        $this->updateTimestampIfNeed($bean);
    }
}
?>
