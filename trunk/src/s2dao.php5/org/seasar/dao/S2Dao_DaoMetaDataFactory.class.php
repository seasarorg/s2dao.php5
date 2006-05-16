<?php

/**
 * @author nowel
 */
interface S2Dao_DaoMetaDataFactory {
    public function getDaoMetaData(ReflectionClass $daoClass);
}
?>
