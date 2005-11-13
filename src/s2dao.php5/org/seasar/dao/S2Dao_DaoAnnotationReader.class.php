<?php

/**
 * @author nowel
 */
interface S2Dao_DaoAnnotationReader {

    function getQuery($name);

    function getArgNames($name);

    function getBeanClass();

    function getNoPersistentProps($methodName);

    function getPersistentProps($methodName);

    function getSQL($name, $suffix);

}
?>
