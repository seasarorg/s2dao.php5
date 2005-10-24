<?php

/**
 * @author nowel
 */
interface DaoAnnotationReader {

    function getQuery($name);

    function getArgNames($name);

    function getBeanClass();

    function getNoPersistentProps($methodName);

    function getPersistentProps($methodName);

    function getSQL($name, $suffix);

}
?>
