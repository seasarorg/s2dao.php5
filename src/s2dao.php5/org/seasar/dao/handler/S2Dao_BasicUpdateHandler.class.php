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
class S2Dao_BasicUpdateHandler extends S2Dao_BasicHandler implements S2Dao_UpdateHandler {

    private static $logger;

    private $rootNode;
    
    private $sqlWrapper;

    private $argNames = array();

    private $argTypes = array();

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_SqlWrapper $sql,
                                array $argNames,
                                array $argTypes,
                                S2Dao_StatementFactory $statementFactory) {
        parent::__construct($dataSource, $sql->getSql(), $statementFactory);
        self::$logger = S2Container_S2Logger::getLogger(get_class($this));
        $parser = new S2Dao_SqlParserImpl($sql->getSql());
        $this->rootNode = $parser->parse();
        $this->sqlWrapper = $sql;
        $this->argNames = $argNames;
        $this->argTypes = $argTypes;
    }
    
    /**
     * @return CommandContext
     */
    protected function createCommandContext(array $args = null) {
        $ctx = new S2Dao_CommandContextImpl();
        if ($args !== null) {
            $c = count($args);
            $typesCount = count($this->argTypes);
            $namesCount = count($this->argNames);
            for ($i = 0; $i < $c; ++$i) {
                $argType = null;
                if ($args[$i] !== null) {
                    if ($i < $typesCount) {
                        $argType = $this->argTypes[$i];
                    } else if ($args[$i] != null) {
                        $argType = gettype($args[$i]);
                    }
                }
                if ($i < $namesCount) {
                    $ctx->addArg($this->argNames[$i], $args[$i], $argType);
                } else {
                    $ctx->addArg('$' . ($i + 1), $args[$i], $argType);
                }
            }
        }
        return $ctx;
    }
    
    public function execute(){
        $args = func_get_args();
        $funcNum = func_num_args();
        if($funcNum == 1){
            if(is_array($args[0])){
                return $this->__call('execute0', $args); 
            }
            if($args[0] instanceof S2Dao_CommandContext){
                return $this->__call('execute1', $args);
            }
        }
        if($funcNum == 2){
            return $this->__call('execute2', $args);
        }
        if($funcNum == 4){
            return $this->__call('execute3', $args);
        }
    }

    public function execute0(array $args) {
        return $this->execute($args, $this->getArgTypes($args));
    }
    
    protected function execute1(S2Dao_CommandContext $ctx) {
        $connection = $this->getConnection();
        $this->sqlWrapper->preUpdateBean($ctx);
        $this->rootNode->accept($ctx);
        $sql = $this->sqlWrapper->transformSql($ctx->getSql());
        $args = $ctx->getBindVariables();
        $argTypes = $ctx->getBindVariableTypes();
        if (self::$logger->isDebugEnabled()) {
            self::$logger->debug($this->_getCompleteSql($sql, $args));
        }
        $ret = $this->execute($connection, $sql, $args, $argTypes);
        $this->sqlWrapper->postUpdateBean($ctx, (int)$ret);
        return $ret;
    }

    public function execute2(array $args, array $argTypes) {
        return $this->execute($this->createCommandContext($args));
    }
    
    protected function execute3(PDO $connection, $sql, array $args, array $argTypes) {
        $ps = $this->_prepareStatement($connection, $sql);
        $this->bindArgs($ps, $args, $argTypes);
        try {
            $ps->execute($ps);
        return $ps->rowCount();
        } catch (PDOException $e) {
            throw new S2Dao_SQLRuntimeException($e);
        }
    }

    protected function _getCompleteSql($sql, array $args) {
        if ($args == null || !is_array($args)) {
            return $sql;
        }
        $pos = 0;
        $buf = $sql;
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

    /**
     * @return PDOStatement
     */
    protected function _prepareStatement($connection, $sql) {
        if ($sql === null) {
            throw new S2Dao_EmptyRuntimeException('sql');
        }
        return $this->statementFactory->createPreparedStatement($connection, $sql);
    }

    private function __call($name, $args){
        return call_user_func_array(array($this, $name), $args);
    }
}

?>