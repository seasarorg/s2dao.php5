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
class S2Dao_ProcedureMetaDataFactory {
    
    public static function createProcedureMetaData(PDO $connection){
        $dbms = S2Dao_DatabaseMetaDataUtil::getDbms($connection);
        
        if($dbms instanceof S2Dao_MySQL){
            return new S2Dao_MySQLProcedureMetaDataImpl($connection, $dbms);
        } else if($dbms instanceof S2Dao_PostgreSQL){
            return new S2Dao_PostgreSQLProcedureMetaDataImpl($connection, $dbms);
        } else if($dbms instanceof S2Dao_SQLite){
            return new S2Dao_SQLiteProcedureMetaDataImpl($connection, $dbms);
        } else {
            throw new Exception('not supported ' . get_class($dbms));
        }
    }
    
}

?>