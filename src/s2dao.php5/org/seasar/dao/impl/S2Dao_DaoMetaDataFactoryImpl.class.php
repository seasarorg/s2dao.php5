<?php

/**
 * @author nowel
 */
class S2Dao_DaoMetaDataFactoryImpl implements S2Dao_DaoMetaDataFactory {

    protected $daoMetaDataCache_ = null;
    protected $dataSource_ = null;
    protected $statementFactory_ = null;
    protected $resultSetFactory_ = null;
    protected $readerFactory_ = null;

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null,
                                S2Dao_ResultSetFactory $resultSetFactory = null,
                                S2Dao_AnnotationReaderFactory $readerFactory) {

        $this->daoMetaDataCache_ = new S2Dao_HashMap();
        $this->dataSource_ = $dataSource;
        $this->statementFactory_ = $statementFactory;
        $this->resultSetFactory_ = $resultSetFactory;
        $this->readerFactory_ = $readerFactory;
    }

    public function getDaoMetaData($daoClass) {
        $key = $daoClass->getName();
        $dmd = $this->daoMetaDataCache_->get($key);
        if ($dmd !== null) {
            return $dmd;
        }
        $dmd = new S2Dao_DaoMetaDataImpl($daoClass,
                                         $this->dataSource_,
                                         $this->statementFactory_,
                                         $this->resultSetFactory_,
                                         $this->readerFactory_);
        $this->daoMetaDataCache_->put($key, $dmd);
        return $dmd;
    }

}
?>
