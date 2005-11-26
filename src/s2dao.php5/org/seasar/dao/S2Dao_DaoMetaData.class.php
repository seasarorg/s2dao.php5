<?php

/**
 * @author nowel
 */
interface S2Dao_DaoMetaData {

    const BEAN = "BEAN";
    const QUERY = "QUERY";
    const FILE = "FILE";

    public function getBeanClass();

    public function getBeanMetaData();

    public function hasSqlCommand($methodName);

    public function getSqlCommand($methodName);

    public function createFindCommand($query);

    public function createFindArrayCommand($query);

    public function createFindBeanCommand($query);

    public function createFindObjectCommand($query);
}
?>
