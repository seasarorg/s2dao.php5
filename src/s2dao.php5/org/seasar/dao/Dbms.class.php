<?php

/**
 * @author nowel
 */
interface Dbms {

    function getAutoSelectSql(BeanMetaData $beanMetaData);
    
    function getSuffix();
    
    function getIdentitySelectString();
    
    function getSequenceNextValString($sequenceName);
}
?>
