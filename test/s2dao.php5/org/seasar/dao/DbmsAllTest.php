<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
// $Id: $
//
/**
 * @author nowel
 */
class DbmsAllTest {
    
    public function __construct(){
    }
    
    public static function main(){
        PHPUnit2_TextUI_TestRunner::run(self::suite());
    }
    
    public static function suite() {
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $dataSource = $container->getComponent("pdo.dataSource");
        $dbms = S2DaoDbmsManager::getDbms($dataSource->getConnection());
        $suite = new PHPUnit2_Framework_TestSuite("All DBMS Tests");
        $suite->addTestSuite('S2DaoDbmsManagerTest');
        if($dbms instanceof S2Dao_Firebird){
            $suite->addTestSuite('S2Dao_FirebirdDBMetaDataTest');
        }
        if($dbms instanceof S2Dao_MySQL){
            $suite->addTestSuite('S2Dao_StandardDBMetaDataTest');
            $suite->addTestSuite('S2Dao_MySQLProcedureMetaDataImplTest');
        }
        if($dbms instanceof S2Dao_Oracle){
            $suite->addTestSuite('S2Dao_OracleDBMetaDataTest');
        }
        if($dbms instanceof S2Dao_PostgreSQL){
            $suite->addTestSuite('S2Dao_PostgreSQLDBMetaDataTest');
            $suite->addTestSuite('S2Dao_PostgreSQLProcedureMetaDataImplTest');
        }
        if($dbms instanceof S2Dao_SQLite){
            $suite->addTestSuite('S2Dao_SQLiteDBMetaDataTest');
            $suite->addTestSuite('S2Dao_SQLiteProcedureMetaDataImplTest');
        }
        if($dbms instanceof S2Dao_Sybase){
            $suite->addTestSuite('S2Dao_SybaseDBMetaDataTest');
        }
        return $suite;
    }
}

?>