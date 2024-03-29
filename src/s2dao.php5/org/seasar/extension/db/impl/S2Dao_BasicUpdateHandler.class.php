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
 * @package org.seasar.extension.db.impl
 */
class S2Dao_BasicUpdateHandler
    extends S2Dao_BasicHandler 
    implements S2Dao_UpdateHandler {

    private static $logger_ = null;
    
    public function __construct(S2Container_DataSource $dataSource,
                                $sql,
                                S2Dao_StatementFactory $statementFactory = null) {
        parent::__construct($dataSource, $sql, $statementFactory);
        self::$logger_ = S2Container_S2Logger::getLogger(get_class($this));
    }
    
    /**
     * @throws S2Dao_SQLRuntimeException
     */
    public function execute($args, $argsTypes = null){
        $stmt = $this->prepareStatement($this->getConnection());
        $this->bindArgs($stmt, $args, $argsTypes);
        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger_->debug(preg_match('/\r?\n/s', ' ', $this->getCompleteSql($args)));
        }

        try {
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new S2Dao_SQLRuntimeException($e);
        }

    }
}

?>