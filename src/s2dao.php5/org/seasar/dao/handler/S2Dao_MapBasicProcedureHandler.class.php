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
// $Id: $
//
/**
 * @author nowel
 * @package org.seasar.s2dao.handler
 */
class S2Dao_MapBasicProcedureHandler extends S2Dao_AbstractBasicProcedureHandler {

    public function __construct(S2Container_DataSource $ds,
                                $procedureName,
                                S2Dao_StatementFactory $statementFactory = null){
        if($statementFactory === null){
            $statementFactory = new S2Dao_BasicStatementFactory();
        }
        $this->setDataSource($ds);
        $this->setProcedureName($procedureName);
        $this->setStatementFactory($statementFactory);
        $this->initTypes();
    }
    
    protected function execute2(PDO $connection, array $args){
        try {
            $stmt = $this->prepareCallableStatement($connection);
            $this->bindArgs($stmt, $args);
            $stmt->execute();
            $result = new S2Dao_HashMap();
            
            $c = count($this->columnInOutTypes_);
            for ($i = 0; $i < $c; $i++) {
                if($this->isOutputColum((int)$this->columnInOutTypes_[$i])){
                    $row = $stmt->fetch(PDO::FETCH_OBJ, $i + 1);
                    $result->put($this->columnNames_[$i], $row[0]);
                }
            }
            return $result;
        } catch (Exception $e) {
            throw new S2Dao_SQLRuntimeException($e);
        }
    }

}
?>
