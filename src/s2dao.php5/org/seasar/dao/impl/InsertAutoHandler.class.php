<?php

/**
 * @author nowel
 */
class InsertAutoHandler extends AbstractAutoHandler {

    public function __construct(DataSource $dataSource,
                                //StatementFactory $statementFactory,
                                $statementFactory,
                                BeanMetaData $beanMetaData,
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
