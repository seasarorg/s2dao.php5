<?php

/**
 * @author nowel
 */
interface S2Dao_Dbms {
    const BIND_TABLE = ':TABLE';
    const BIND_COLUMN = ':COLUMN';
    
    function getAutoSelectSql(S2Dao_BeanMetaData $beanMetaData);
    function getSuffix();
    function getIdentitySelectString();
    function getSequenceNextValString($sequenceName);
    function getTableSql();
    function getTableInfoSql();
    function getPrimaryKeySql();
    function getProcedureNamesSql();
    function analysProcedureParams($params);
}
?>
