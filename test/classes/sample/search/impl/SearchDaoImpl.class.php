<?php

class SearchDaoImpl extends S2Dao_AbstractDao implements SearchDao {
    
    public function __construct(S2Dao_DaoMetaDataFactory $daoMetaDataFactory) {
        parent::__construct($daoMetaDataFactory);
    }
    
    public function get1List(){
        return $this->getEntityManager()->find("DEPTNO > 0");
    }
    
    public function get2List(){
        return $this->getEntityManager()->find("DEPTNO = ?", 30);
    }
    
    public function get3List(){
        return $this->getEntityManager()->find("order by DEPTNO DESC", "");
    }
    
    public function get4List(){
        return $this->getEntityManager()->find("DEPTNO = ? order by DEPTNO", 30);
    }
    
    public function get5List(){
        return $this->getEntityManager()->find("where DEPTNO = 30");
    }
    
    public function get6List(){
        return $this->getEntityManager()->find("DEPTNO between ? and ?", 10, 20);
    }
    
    public function get7List(){
        return $this->getEntityManager()->find("WHERE DEPTNO between 10 and 20");
    }
}

?>