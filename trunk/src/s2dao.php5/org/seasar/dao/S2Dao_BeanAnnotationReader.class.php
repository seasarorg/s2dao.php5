<?php

/**
 * @author nowel
 */
interface S2Dao_BeanAnnotationReader {
    function getColumnAnnotation(S2Container_PropertyDesc $pd);

    function getTableAnnotation();

    function getVersionNoPropertyNameAnnotation();

    function getTimestampPropertyName();

    function getId(S2Container_PropertyDesc $pd);

    function getNoPersisteneProps();

    function hasRelationNo(S2Container_PropertyDesc $pd);

    function getRelationNo(S2Container_PropertyDesc $pd);

    function getRelationKey(S2Container_PropertyDesc $pd);
}
?>
