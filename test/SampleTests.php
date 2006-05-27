<?php
require_once dirname(__FILE__) . "/test.environment.php";
require_once dirname(__FILE__) . "/setup.php";

class SampleTests {
    
    public function __construct(){}
    
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite(){
        $suites = new PHPUnit2_Framework_TestSuite("All Sample tests");
        return $suites;
    }
}

?>