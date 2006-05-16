<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractStaticCommand extends S2Dao_AbstractSqlCommand {

    private $beanMetaData_;

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null,
                                S2Dao_BeanMetaData $beanMetaData) {

        parent::__construct($dataSource, $statementFactory);
        $this->beanMetaData_ = $beanMetaData;
    }

    public function getBeanMetaData() {
        return $this->beanMetaData_;
    }
}
?>
