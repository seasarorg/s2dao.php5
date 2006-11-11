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
 */
class S2Dao_BasicHandler {

    protected $dataSource;
    protected $sql;
    protected $statementFactory = null;

    public function __construct(S2Container_DataSource $ds,
                                $sql,
                                S2Dao_StatementFactory $statementFactory) {
        $this->setDataSource($ds);
        $this->setSql($sql);
        if($statementFactory === null){
            $this->setStatementFactory(new S2Dao_BasicStatementFactory());
        } else {
            $this->setStatementFactory($statementFactory);
        }
    }
    
    public function getDataSource() {
        return $this->dataSource;
    }

    public function setDataSource(S2Container_DataSource $dataSource) {
        $this->dataSource = $dataSource;
    }

    public function getSql() {
        return $this->sql;
    }

    public function setSql($sql) {
        $this->sql = $sql;
    }

    public function getStatementFactory() {
        return $this->statementFactory;
    }

    public function setStatementFactory($statementFactory) {
        $this->statementFactory = $statementFactory;
    }
    
    private function setAttributes(PDO $connection){
        if(!(S2DaoDbmsManager::getDbms($connection) instanceof S2Dao_MySQL)){
            return;
        }
        // php 5.1.3 or later
        if(!version_compare(phpversion(), '5.1.3', '>=')){
            return;
        }
        // FIXME pdo version 1.0.3 higher
        $refPdo = new ReflectionExtension('PDO');
        if(!version_compare($refPdo->getVersion(), '1.0.3', '>=')){
            return;
        }
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
    }

    protected function getConnection() {
        if ($this->dataSource == null) {
            throw new S2Container_EmptyRuntimeException('dataSource');
        }
        $conn = $this->dataSource->getConnection();
        $this->setAttributes($conn);
        return $conn;
    }

    protected function prepareStatement(PDO $connection) {
        if ($this->sql == null) {
            throw new S2Container_EmptyRuntimeException('sql');
        }
        return $this->statementFactory->createPreparedStatement($connection, $this->sql);
    }
    
    protected function bindArgs(PDOStatement $ps,
                                array $args = null,
                                array $argTypes = null) {
        if ($args === null) {
            return;
        }

        $c = count($args);
        for ($i = 0; $i < $c; $i++) {
            try {
                $arg = $args[$i];
                $argType = $argTypes[$i];
                $phpType = gettype($arg);
                
                // TODO: argTypes check value
                //$ps->bindValue($i + 1, $arg, $this->getBindParamTypes($phpType));
                
                // FIXME: null check null value
                if($argType !== null){
                    if($this->isNullType($arg, $argType)){
                        $ps->bindValue($i + 1, $arg, S2Dao_PDOType::Null);
                    } else {
                        $ps->bindValue($i + 1, $arg, $this->getBindParamTypes($argType));
                    }
                } else {
                    $ps->bindValue($i + 1, $arg, $this->getBindParamTypes($phpType));
                }
            } catch (Exception $ex) {
                throw new S2Container_SQLRuntimeException($ex);
            }
        }
    }

    protected function getArgTypes($args) {
        if ($args == null) {
            return null;
        }
        $argTypes = array();
        $c = count($args);
        for ($i = 0; $i < $c; ++$i) {
            $arg = $args[$i];
            if ($arg != null) {
                $argTypes[$i] = get_class($arg);
            }
        }
        return $argTypes;
    }

    protected function getCompleteSql($args = null) {
        if ($args == null || !is_array($args)) {
            return $this->sql;
        }
        $pos = 0;
        $buf = $this->sql;
        foreach($args as $value){
            $pos = strpos($buf, '?');
            if($pos !== false){
                $buf = substr_replace($buf, $this->getBindVariableText($value), $pos, 1);
            } else {
                break;
            }
        }
        return $buf;
    }

    protected function getBindParamTypes($phpType = null){
        return S2Dao_PDOType::gettype($phpType);
    }

    protected function getBindVariableText($bindVariable) {
        if (is_string($bindVariable)) {
            return "'" . $bindVariable . "'";
        } else if (is_numeric($bindVariable)) {
            return (string)$bindVariable;
        } else if (is_long($bindVariable)) {
            return "'" . date("Y-m-d H.i.s", $bindVariable) . "'";
        } else if (is_bool($bindVariable)) {
            return (string)$bindVariable;
        } else if ($bindVariable === null) {
            return "null";
        } else if (strtotime($bindVariable) !== -1) {
            return "'" . date("Y-m-d", strtotime($bindVariable)) . "'";
        } else {
            return "'" . (string)$bindVariable . "'";
        }
    }
    
    private function isNullType($value = null, $type = null){
        $nullString = gettype(null);
        if($type == $nullString && gettype($value) == $nullString){
            return true;
        }
        if(!$this->isPrimitive($type)){
            return true;
        }
        return false;
    }
    
    private function isPrimitive($type){
        return $type != gettype(new stdClass);
    }
}
?>
