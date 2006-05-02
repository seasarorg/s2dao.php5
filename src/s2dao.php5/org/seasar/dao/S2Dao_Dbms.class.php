<?php

/**
 * @author nowel
 */
interface S2Dao_Dbms {
    
    const BIND_TABLE = ':TABLE';
    const BIND_COLUMN = ':COLUMN';
    const BIND_DB = ':DB';
    const BIND_SCHEME = ':SCHEME';
    const BIND_CATALOG = ':CATALOG';
    const BIND_NAME = ':NAME';
    
    function getAutoSelectSql(S2Dao_BeanMetaData $beanMetaData);
    function getSuffix();
    function getIdentitySelectString();
    function getSequenceNextValString($sequenceName);
    function getTableSql();
    function getTableInfoSql();
    function getPrimaryKeySql();
    function getProcedureNamesSql();
    function getProcedureInfoSql();
}
?>
