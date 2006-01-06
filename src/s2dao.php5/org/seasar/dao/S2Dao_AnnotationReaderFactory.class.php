<?php

/**
 * @author nowel
 */
interface S2Dao_AnnotationReaderFactory {
    public function createDaoAnnotationReader(S2Container_BeanDesc $daoBeanDesc);
    public function createBeanAnnotationReader($beanClass_);
}
?>
