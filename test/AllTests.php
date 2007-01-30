<?php
require_once dirname(__FILE__) . "/test.environment.php";
require_once dirname(__FILE__) . "/setup.php";
require_once dirname(__FILE__) . "/S2DaoTests.php";
require_once dirname(__FILE__) . "/ExtensionTests.php";
require_once dirname(__FILE__) . "/SampleTests.php";

class AllTests {
    
    public function __construct($name){
        parent::__construct($name);
    }
    
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite(){
        $suites = new PHPUnit2_Framework_TestSuite("All S2Dao.PHP5 Tests");
        $suites->addTest(S2DaoTests::suite());
        $suites->addTest(ExtensionTests::suite());
        $suites->addTest(SampleTests::suite());
        return $suites;
    }
}

?>
