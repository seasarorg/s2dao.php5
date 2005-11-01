<?php

/**
 * @author nowel
 */
abstract class AbstractStaticCommand extends AbstractSqlCommand {

    private $beanMetaData_;

    public function __construct(DataSource $dataSource,
                                //StatementFactory $statementFactory,
                                $statementFactory,
                                BeanMetaData $beanMetaData) {

        parent::__construct($dataSource, $statementFactory);
        $this->beanMetaData_ = $beanMetaData;
    }

    public function getBeanMetaData() {
        return $this->beanMetaData_;
    }
}
?>
