<?php

/**
 * @author nowel
 */
class S2Dao_FieldAnnotationReaderFactory implements S2Dao_AnnotationReaderFactory {
    public function createDaoAnnotationReader(S2Container_BeanDesc $daoBeanDesc) {
        return new S2Dao_FieldDaoAnnotationReader($daoBeanDesc);
    }
    public function createBeanAnnotationReader($beanClass_) {
        return new S2Dao_FieldBeanAnnotationReader($beanClass_);
    }
}

?>
