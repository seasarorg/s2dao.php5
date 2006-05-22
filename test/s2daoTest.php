<?php
require_once dirname(__FILE__) . "/test.condition.php";
//require_once dirname(__FILE__) . "/setup.php";

S2ContainerClassLoader::import(PACKAGE_DIR . "/dao");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/annotation");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/annotation/type");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/context");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/dbms");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/id");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/impl");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/interceptors");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/pager");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/parser");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/unit");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/util");
S2ContainerClassLoader::import(PACKAGE_DIR . "/dao/util/procedure");

class s2daoTests {
    function __construct(){}
    
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite(){
        $suites = new PHPUnit2_Framework_TestSuite("All S2DAO_PHP5 tests");
        $suites->addTest(AnnotationAllTest::suite());
        return $suites;
    }
}

s2daoTests::main();
echo PHP_EOL;
?>