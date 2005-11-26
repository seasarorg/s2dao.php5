<?php

/**
 * @author nowel
 */
interface S2Dao_Dbms {
    function getAutoSelectSql(S2Dao_BeanMetaData $beanMetaData);
    function getSuffix();
    function getIdentitySelectString();
    function getSequenceNextValString($sequenceName);
}
?>
