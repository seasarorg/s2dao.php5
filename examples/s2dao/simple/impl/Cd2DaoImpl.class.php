<?php

class Cd2DaoImpl extends S2Dao_AbstractDao implements Cd2Dao {

    public function __construct(S2Dao_DaoMetaDataFactory $daoMetaDataFactory){
        parent::__construct($daoMetaDataFactory);
    }

    public function getCd($id){
        return $this->getEntityManager()->find("ID = ?", $id);
        //return $this->getEntityManager()->findArray("ID = ?", $id);
        //return $this->getEntityManager()->findBean("ID = ?", $id);
        //return $this->getEntityManager()->findObject("ID = ?", $id);
    }
}

?>
