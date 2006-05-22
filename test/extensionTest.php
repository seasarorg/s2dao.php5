<?php
require_once dirname(__FILE__) . "/test.condition.php";
//require_once dirname(__FILE__) . "/setup.php";

S2ContainerClassLoader::import(PACKAGE_DIR . "/extension/dataset");
S2ContainerClassLoader::import(PACKAGE_DIR . "/extension/db");
S2ContainerClassLoader::import(PACKAGE_DIR . "/extension/tx");

class extensionTests {
    function __construct(){}
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    public static function suite(){
        $suites = new PHPUnit2_Framework_TestSuite("All S2DAO_PHP5 Extensions tests");
        return $suites;
    }
}

extensionTests::main();

?>