<?php

/**
 * @author nowel
 */
class S2Dao_FieldBeanAnnotationReader extends S2Dao_AbstractAnnotationReader {
    
    public function __construct($beanClass){
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($beanClass);
        parent::__construct($beanDesc,
                            new S2Dao_BeanCommentAnnotationReader($beanDesc),
                            new S2Dao_BeanConstantAnnotationReader($beanDesc));
    }

}
?>
