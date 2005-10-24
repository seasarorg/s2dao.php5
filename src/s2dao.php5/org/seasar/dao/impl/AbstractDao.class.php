<?php

/**
 * @author nowel
 */
abstract class AbstractDao {

    private $entityManager_;

    public function __construct(DaoMetaDataFactory $daoMetaDataFactory) {
        $this->entityManager_ = new EntityManagerImpl(
                        $daoMetaDataFactory->getDaoMetaData(__CLASS__));
    }

    public function getEntityManager() {
        return $this->entityManager_;
    }
}
?>
