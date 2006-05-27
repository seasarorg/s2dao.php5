<?php
require_once "S2DaoTests.php";
require_once "ExtensionTests.php";
require_once "SampleTests.php";

class AllTests {
    
    function __construct(){}
    
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite(){
        $suites = new PHPUnit2_Framework_TestSuite(__CLASS__);
        $suites->addTest(S2DaoTests::suite());
        $suites->addTest(ExtensionTests::suite());
        $suites->addTest(SampleTests::suite());
        return $suites;
    }
}


?>