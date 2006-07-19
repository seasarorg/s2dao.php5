<?php
require_once dirname(__FILE__) . "/test.environment.php";
require_once dirname(__FILE__) . "/setup.php";

S2ContainerClassLoader::import(SAMPLE_DIR);
S2ContainerClassLoader::import(SAMPLE_DIR . "/transaction");
S2ContainerClassLoader::import(SAMPLE_DIR . "/search");

/**
 * @author nowel
 */
class SampleTests {
    
    public function __construct(){}
    
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite(){
        $suites = new PHPUnit2_Framework_TestSuite("All Sample tests");
        //$suites->addTest(TransactionTest::suite());
        $suites->addTest(SearchTest::suite());
        return $suites;
    }
}

?>