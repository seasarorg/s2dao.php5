<?php

/**
 * @author nowel
 */
interface S2Dao_DaoAnnotationReader {
    function getQuery(ReflectionMethod $method);

    function getArgNames(ReflectionMethod $method);

    function getBeanClass();

    function getNoPersistentProps(ReflectionMethod $method);

    function getPersistentProps(ReflectionMethod $method);

    function getSQL(ReflectionMethod $method, $suffix);
}
?>
