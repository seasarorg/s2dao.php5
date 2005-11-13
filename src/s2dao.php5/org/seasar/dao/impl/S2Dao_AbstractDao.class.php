<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractDao {

    private $entityManager_;

    public function __construct(DaoMetaDataFactory $daoMetaDataFactory) {
        $this->entityManager_ = new S2Dao_EntityManagerImpl(
                        $daoMetaDataFactory->getDaoMetaData(__CLASS__));
    }

    public function getEntityManager() {
        return $this->entityManager_;
    }
}
?>
