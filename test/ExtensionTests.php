<?php
require_once dirname(__FILE__) . "/test.environment.php";
require_once dirname(__FILE__) . "/setup.php";

S2ContainerClassLoader::import(PACKAGE_DIR . "/extension");
S2ContainerClassLoader::import(PACKAGE_DIR . "/extension/dataset");
S2ContainerClassLoader::import(PACKAGE_DIR . "/extension/db");
S2ContainerClassLoader::import(PACKAGE_DIR . "/extension/tx");

class ExtensionTests {
    
    public function __construct(){}
    
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    public static function suite(){
        $suites = new PHPUnit2_Framework_TestSuite("All S2Dao.PHP5 Extensions tests");
        $suites->addTest(ExDatasetAllTest::suite());
        $suites->addTest(ExDbAllTest::suite());
        $suites->addTest(ExTxAllTest::suite());
        return $suites;
    }
}

?>