<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractDao {

    private $entityManager_;

    public function __construct(S2Dao_DaoMetaDataFactory $daoMetaDataFactory) {
        $this->entityManager_ = new S2Dao_EntityManagerImpl(
                    $daoMetaDataFactory->getDaoMetaData(new ReflectionClass($this))
                    );
    }

    public function getEntityManager() {
        return $this->entityManager_;
    }
}
?>
