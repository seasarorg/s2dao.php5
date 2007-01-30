<?php
class Employee2DaoImpl extends S2Dao_AbstractDao implements Employee2Dao {

    const BEAN = "Employee";

    public function __construct(S2Dao_DaoMetaDataFactory $daoMetaDataFactory){
        parent::__construct($daoMetaDataFactory);
    }

    public function getEmployees($ename) {
        return $this->getEntityManager()->find("EMP.ENAME LIKE ? ", "%" . $ename . "%");
    }

    public function getEmployee($eno){
        return $this->getEntityManager()->find(
                    "SELECT * FROM EMP WHERE EMPNO = ?", $eno);
    }

}
?>
