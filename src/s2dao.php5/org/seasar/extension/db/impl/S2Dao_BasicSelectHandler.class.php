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
 * @package org.seasar.extension.db.impl
 */
class S2Dao_BasicSelectHandler extends S2Dao_BasicHandler implements S2Dao_SelectHandler {

    private static $logger;
    private $resultSetFactory;
    private $resultSetHandler;
    private $fetchSize = 100;
    private $maxRows = -1;

    public function __construct(S2Container_DataSource $dataSource,
                              $sql,
                              S2Dao_ResultSetHandler $resultSetHandler,
                              S2Dao_StatementFactory $statementFactory = null,
                              S2Dao_ResultSetFactory $resultSetFactory = null) {
        parent::__construct($dataSource, $sql, $statementFactory);
        self::$logger = S2Container_S2Logger::getLogger(get_class($this));
        $this->setResultSetHandler($resultSetHandler);
        if($resultSetFactory === null){
            $this->setResultSetFactory(new S2Dao_BasicResultSetFactory());
        } else {
            $this->setResultSetFactory($resultSetFactory);
        }
    }

    public function getResultSetFactory() {
        return $this->resultSetFactory;
    }

    public function setResultSetFactory(S2Dao_ResultSetFactory $resultSetFactory = null) {
        $this->resultSetFactory = $resultSetFactory;
    }

    public function getResultSetHandler() {
        return $this->resultSetHandler;
    }

    public function setResultSetHandler(S2Dao_ResultSetHandler $resultSetHandler = null) {
        $this->resultSetHandler = $resultSetHandler;
    }

    public function getFetchSize() {
        return $this->fetchSize;
    }

    public function setFetchSize($fetchSize) {
        $this->fetchSize = $fetchSize;
    }

    public function getMaxRows() {
        return $this->maxRows;
    }

    public function setMaxRows($maxRows) {
        $this->maxRows = $maxRows;
    }

    /**
     * @throws S2Container_EmptyRuntimeException
     * @throws S2Dao_SQLRuntimeException
     */
    public function execute($args1, $args2){
        $stmt = $this->prepareStatement($this->getConnection());
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $this->bindArgs($stmt, $args1, $args2);
        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger->debug(preg_replace('/\r?\n/s', ' ', $this->getCompleteSql($args1)));
        }
        if ($this->resultSetHandler === null) {
            throw new S2Container_EmptyRuntimeException('resultSetHandler');
        }

        try{
            $stmt->execute();
            $resultSet = $this->createResultSet($stmt);
            $columnCount = $stmt->columnCount();
            if($columnCount == 1){
                $rs = $stmt->fetch(PDO::FETCH_NUM);
                return $rs[0];
            } else if($columnCount === null || $columnCount <= 0){
                return null;
            } else {
                return $this->resultSetHandler->handle($stmt);
            }
        } catch (PDOException $ex) {
            throw new S2Dao_SQLRuntimeException($ex);
        }
    }

    protected function setup($con, $args) {
        return $args;
    }

    protected function setupDatabaseMetaData(DatabaseMetaData $dbMetaData) {
    }

    protected function createResultSet(PDOStatement $stmt) {
        return $this->resultSetFactory->createResultSet($stmt);
    }
}
?>
