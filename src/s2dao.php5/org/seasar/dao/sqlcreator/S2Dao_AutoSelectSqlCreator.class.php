<?php

/**
 * @author nowel
 */
interface S2Dao_AutoSelectSqlCreator {
    public function createSelectSql(
            S2Dao_Dbms $dbms,
            S2Dao_BeanMetaData $beanMetaData,
            array $joinData = null,
            $query);
}
?>