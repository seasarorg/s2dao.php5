<?php
require_once dirname(__FILE__) . "/test.condition.php";
//require_once dirname(__FILE__) . "/setup.php";

class sampleTests {
    function __construct(){}
    
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite(){
        $suites = new PHPUnit2_Framework_TestSuite("All Sample tests");
        return $suites;
    }
}

sampleTests::main();

?>