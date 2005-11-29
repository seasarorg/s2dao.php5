<?php
class CdDaoImpl extends S2Dao_AbstractDao implements absExampleCdDao {

    public function __construct(S2Dao_DaoMetaDataFactory $daoMetaDataFactory){
        parent::__construct($daoMetaDataFactory);
    }

    public function getCd($id){
        return $this->getEntityManager()->find("ID = ".$id);
    }
}

?>
