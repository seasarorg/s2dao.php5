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
// $Id$
//
/**
 * @author nowel
 */
abstract class S2Dao_AbstractSqlCommand implements S2Dao_SqlCommand {

    private $dataSource_;
    private $statementFactory_;
    private $sql_;
    private $notSingleRowUpdatedExceptionClass_;

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null) {
        
        $this->dataSource_ = $dataSource;
        $this->statementFactory_ = $statementFactory;
    }

    public function getDataSource() {
        return $this->dataSource_;
    }
    
    public function getStatementFactory() {
        return $this->statementFactory_;
    }

    public function getSql() {
        return $this->sql_;
    }

    public function setSql($sql) {
        $this->sql_ = $sql;
    }
    
    public function getNotSingleRowUpdatedExceptionClass() {
        return $this->notSingleRowUpdatedExceptionClass_;
    }
    
    public function setNotSingleRowUpdatedExceptionClass($notSingleRowUpdatedExceptionClass) {
        $this->notSingleRowUpdatedExceptionClass_ = $notSingleRowUpdatedExceptionClass;
    }

}
?>
