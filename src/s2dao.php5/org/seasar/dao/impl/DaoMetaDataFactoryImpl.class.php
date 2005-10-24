<?php

/**
 * @author Yusuke Hata
 */
class DaoMetaDataFactoryImpl implements DaoMetaDataFactory {

    protected $daoMetaDataCache_ = null;
    protected $dataSource_ = null;
    protected $statementFactory_ = null;
    protected $resultSetFactory_ = null;

    public function __construct(DataSource $dataSource,
                                $statementFactory,
                                $resultSetFactory) {

        $this->daoMetaDataCache_ = new HashMap();

        $this->dataSource_ = $dataSource;
        $this->statementFactory_ = $statementFactory;
        $this->resultSetFactory_ = $resultSetFactory;
    }

    public function getDaoMetaData($daoClass) {
        $key = $daoClass->getName();
        $dmd = $this->daoMetaDataCache_->get($key);
        if ($dmd !== null) {
            return $dmd;
        }
        
        $dmd = new DaoMetaDataImpl($daoClass,
                                    $this->dataSource_,
                                    $this->statementFactory_,
                                    $this->resultSetFactory_);
        $this->daoMetaDataCache_->put($key, $dmd);
        
        return $dmd;
    }

}
?>
