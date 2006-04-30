<?php

/**
 * @author nowel
 */
class S2Dao_FieldDaoAnnotationReader extends  S2Dao_AbstractAnnotationReader {
    
    public function __construct(S2Container_BeanDesc $daoBeanDesc) {
        parent::__construct($daoBeanDesc,
                new S2Dao_DaoCommentAnnotationReader($daoBeanDesc),
                new S2Dao_DaoConstantAnnotationReader($daoBeanDesc));
    }

}
?>
