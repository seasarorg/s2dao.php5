<?php

abstract class Employee2DaoImpl extends S2Dao_AbstractDao implements Employee2Dao {

    public function __construct(S2Dao_DaoMetaDataFactory $daoMetaDataFactory) {
        parent::__construct($daoMetaDataFactory);
    }

    public function getEmployeesByDeptnoArray($deptno) {
        return $this->getEntityManager()->findArray("EMP2.deptno = ?", $deptno);
    }
}

?>