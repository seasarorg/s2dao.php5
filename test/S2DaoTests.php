<?php
require_once dirname(__FILE__) . "/test.environment.php";
require_once dirname(__FILE__) . "/setup.php";

S2ContainerClassLoader::import(PACKAGE_DIR . "/dao");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/annotation");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/annotation/type");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/dbms");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/dbms/dbmeta");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/dbms/procedure");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/id");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/impl");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/interceptors");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/pager");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/parser");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/resultset");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/unit");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/util");

/**
 * @author nowel
 */
class S2DaoTests {
    
    public function __construct(){}
    
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite(){
        $suites = new PHPUnit2_Framework_TestSuite("S2Dao.PHP5 All Tests");
        $suites->addTest(AnnotationAllTest::suite());
        $suites->addTest(DbmsAllTest::suite());
        $suites->addTest(IdAllTest::suite());
        $suites->addTest(InterceptorAllTest::suite());
        $suites->addTest(PagerAllTest::suite());
        $suites->addTest(ParserAllTest::suite());
        $suites->addTest(S2DaoImplAllTest::suite());
        $suites->addTest(ResultSetAllTest::suite());
        $suites->addTest(UnitAllTest::suite());
        $suites->addTest(UtilAllTest::suite());
        return $suites;
    }
}

?>
