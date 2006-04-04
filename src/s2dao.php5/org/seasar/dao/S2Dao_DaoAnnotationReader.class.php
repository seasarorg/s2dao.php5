<?php

/**
 * @author nowel
 */
interface S2Dao_DaoAnnotationReader {

    function getBeanClass();

    function getQuery(ReflectionMethod $method);

    function getArgNames(ReflectionMethod $method);

    function getNoPersistentProps(ReflectionMethod $method);

    function getPersistentProps(ReflectionMethod $method);

    function getSQL(ReflectionMethod $method, $suffix);
    
    function isSelectList(ReflectionMethod $method);
    
    function isSelectArray(ReflectionMethod $method);
}
?>
