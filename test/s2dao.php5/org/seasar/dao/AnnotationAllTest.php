<?php

class AnnotationAllTest {
    
    public function __construct(){
    }
    
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite() {
        $suite = new PHPUnit2_Framework_TestSuite("Annotation All Test");
        $suite->addTestSuite('S2Dao_AbstractAnnotationReaderTest');
        $suite->addTestSuite('S2Dao_BeanCommentAnnotationReaderTest');
        $suite->addTestSuite('S2Dao_BeanConstantAnnotationReaderTest');
        $suite->addTestSuite('S2Dao_DaoCommentAnnotationReaderTest');
        $suite->addTestSuite('S2Dao_DaoConstantAnnotationReaderTest');
        $suite->addTestSuite('S2Dao_FieldAnnotationReaderFactoryTest');
        $suite->addTestSuite('S2Dao_FieldBeanAnnotationReaderTest');
        $suite->addTestSuite('S2Dao_FieldDaoAnnotationReaderTest');
        $suite->addTestSuite('S2DaoAnnotationReaderTest');
        return $suite;
    }
}

?>