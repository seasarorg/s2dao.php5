<?php

/**
 * @author nowel
 */
class S2Dao_BasicHandler {

    private $dataSource_;
    private $sql_;
    private $statementFactory_ = null;

    public function __construct(S2Container_DataSource $ds, $sql, $statementFactory = null) {
        if(is_null($statementFactory)){
            $this->setDataSource($ds);
            $this->setSql($sql);
            $this->setStatementFactory(new S2Dao_BasicStatementFactory);
        } else {
            $this->setDataSource($ds);
            $this->setSql($sql);
            $this->setStatementFactory($statementFactory);
        }
    }

    public function getDataSource() {
        return $this->dataSource_;
    }

    public function setDataSource(S2Container_DataSource $dataSource) {
        $this->dataSource_ = $dataSource;
    }

    public function getSql() {
        return $this->sql_;
    }

    public function setSql($sql) {
        $this->sql_ = $sql;
    }

    public function getStatementFactory() {
        return $this->statementFactory_;
    }

    public function setStatementFactory($statementFactory) {
        $this->statementFactory_ = $statementFactory;
    }

    protected function getConnection() {
        if ($this->dataSource_ == null) {
            throw new S2Container_EmptyRuntimeException('dataSource');
        }
        return S2Dao_DataSourceUtil::getConnection($this->dataSource_);
    }

    protected function prepareStatement(PDO $connection) {
        if ($this->sql_ == null) {
            throw new S2Container_EmptyRuntimeException('sql');
        }
        return $connection->prepare($this->sql_);
    }

    protected function bindArgs(PDOStatement $ps, $args, $argTypes) {
        if ($args === null) {
            return;
        }

        $c = count($args);
        for ($i = 0; $i < $c; $i++) {
            try {
                if($argTypes[$i] !== null){
                    $ps->bindValue($i + 1, $args[$i], $this->getBindParamTypes($argTypes[$i]));
                } else {
                    $ps->bindValue($i + 1, $args[$i]);
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
        for ($i = 0; $i < count($args); ++$i) {
            $arg = $args[$i];
            if ($arg != null) {
                $argTypes[$i] = get_class($arg);
            }
        }
        return $argTypes;
    }

    protected function getCompleteSql($args = null) {
        if ($args == null || !is_array($args)) {
            return $this->sql_;
        }
        $pos = 0;
        $buf = $this->sql_;
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
        } else if ($bindVariable == null) {
            return "null";
        } else if (strtotime($bindVariable) !== -1 ) {
            return "'" . date("Y-m-d", strtotime($bindVariable)) . "'";
        } else {
            return "'" . (string)$bindVariable . "'";
        }
    }
}
?>
