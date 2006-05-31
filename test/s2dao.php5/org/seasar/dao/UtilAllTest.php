<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id$
//
/**
 * @author nowel
 */
class UtilAllTest {
    
    public function __construct(){
    }
    
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite() {
        $suite = new PHPUnit2_Framework_TestSuite("All Util Tests");
        $suite->addTestSuite('S2Dao_ArrayListTest');
        $suite->addTestSuite('S2Dao_ArrayUtilTest');
        $suite->addTestSuite('S2Dao_DatabaseMetaDataUtilTest');
        $suite->addTestSuite('S2Dao_HashMapTest');
        //$suite->addTestSuite('S2Dao_MySQLProcedureMetaDataImplTest');
        //$suite->addTestSuite('S2Dao_OracleProcedureMetaDataImplTest');
        //$suite->addTestSuite('S2Dao_PostgreSQLProcedureMetaDataImplTest');
        $suite->addTestSuite('S2Dao_ProcedureInfoTest');
        $suite->addTestSuite('S2Dao_ProcedureMetaDataFactoryTest');
        $suite->addTestSuite('S2Dao_ProcedureTypeTest');
        //$suite->addTestSuite('S2Dao_SQLiteProcedureMetaDataImplTest');
        return $suite;
    }
}

?>