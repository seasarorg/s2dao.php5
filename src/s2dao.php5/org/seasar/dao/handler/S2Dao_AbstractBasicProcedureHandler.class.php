<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractBasicProcedureHandler implements S2Dao_ProcedureHandler{
    
    protected $initialised = false;
    protected $dataSource_;
    protected $procedureName_;
    protected $sql_;
    protected $columnInOutTypes_ = array();
    protected $columnTypes_ = array();
    protected $columnNames_ = array();
    protected $statementFactory_ = null;

    public function getDataSource() {
        return $this->dataSource_;
    }

    public function setDataSource(S2Container_DataSource $dataSource) {
        $this->dataSource_ = $dataSource;
    }
    public function getProcedureName() {
        return $this->procedureName_;
    }
    public function setProcedureName($procedureName) {
        $this->procedureName_ = $procedureName;
    }

    public function getStatementFactory() {
        return $this->statementFactory_;
    }

    public function setStatementFactory(S2Dao_StatementFactory $statementFactory) {
        $this->statementFactory_ = $statementFactory;
    }

    protected function getConnection() {
        if ($this->dataSource_ === null) {
            throw new S2Container_EmptyRuntimeException('dataSource');
        }
        return S2Dao_DataSourceUtil::getConnection($this->dataSource_);
    }

    protected function prepareCallableStatement(PDO $connection) {
        if ($this->sql_ == null) {
            throw new S2Container_EmptyRuntimeException('sql');
        }
        return $this->statementFactory_->createCallableStatement(
                        $connection, $this->sql_);
    }

    protected function confirmProcedureName($dmd) {
        $str = S2Dao_DatabaseMetaDataUtil::convertIdentifier($dmd, $this->procedureName_);
        $names = split('\\.', $str);
        $namesLength = count($names);
        $rs = null;
        try{
            if($namesLength == 1){
                $rs = $dmd->getProcedures(null, null, $names[0]);
            }else if($namesLength == 2){
                $rs = $dmd->getProcedures($names[0], null, $names[1]);
            }else if($namesLength == 3){
                $rs = $dmd->getProcedures($names[0], $names[1], $names[2]);
            }
            $len = 0;
            $names = '';
            while($rs->next()){
                $names[0] = $rs->getString(1);
                $names[1] = $rs->getString(2);
                $names[2] = $rs->getString(3);
                $len++;
            }
            if($len < 1){
                throw new S2Container_S2RuntimeException('EDAO0012',
                                            array($this->procedureName_));
            }
            if($len > 1){
                throw new S2Container_S2RuntimeException('EDAO0013',
                                            array($this->procedureName_));
            }
            return $names;
        }
    }
    
    protected function initTypes(){
        $buff = '';
        $buff .= '{ call ';
        $buff .= $this->procedureName_;
        $buff .= '(';
        $columnNames = new S2Dao_ArrayList();
        $dataType = new S2Dao_ArrayList();
        $inOutTypes = new S2Dao_ArrayList();
        $rs = null;
        $outparameterNum = 0;
        $connection = null;
        try{
            $connection = $this->getConnection();
            $dmd = S2Dao_ConnectionUtil::getMetaData($connection);
            $names = $this->confirmProcedureName($dmd);
            $rs = $dmd->getProcedureColumns($names[0], $names[1], $names[2],null);
            while($rs->next()){
                $columnNames->add($rs->getObject(4));
                $columnType = $rs->getInt(5);
                $inOutTypes->add((int)$columnType);
                $dataType->add((int)$rs->getInt(6));
                if($columnType == S2Dao_DatabaseMetaData::procedureColumnIn){
                    $buff .= '?,';
                } else if($columnType == S2Dao_DatabaseMetaData::procedureColumnReturn){
                    $buf = '';
                    $buff .= '{? = call ';
                    $buff .= $this->procedureName_;
                    $buff .= '(';
                } else if($columnType == S2Dao_DatabaseMetaData::procedureColumnOut ||
                        $columnType == S2Dao_DatabaseMetaData::procedureColumnInOut){
                    $buff .= '?,';
                    $outparameterNum++;
                } else {
                    throw new S2Container_RuntimeException('EDAO0010',
                                                        array($this->procedureName_));
                }
            }            
        } catch (Exception $e) {
            throw new S2Dao_SQLRuntimeException($e);
        }
        $buff .= ')}';
        $this->sql_ = $buff;
        $this->columnNames_ = $columnNames->toArray();
        $this->columnTypes_ = $dataType->toArray();
        $this->columnInOutTypes_ = $inOutTypes->toArray();
        return $outparameterNum;
    }

    public function execute() {
        if(1 < func_num_args()){
            $connection = func_get_arg(0);
            $args = func_get_arg(1);
            
        } else {
            $args = func_get_arg(0);
            $connection = $this->getConnection();
            return $this->execute($connection, $args);
        }
    }
    
    protected abstract function execute(PDO $connection, array $args);
    
    protected function bindArgs(PDOStatement $ps, array $args = null) {
        if ($args == null) {
            return;
        }
        $argPos = 0;
        $c = count($this->columnTypes_);
        for ($i = 0; $i < $c; $i++) {
            if($this->isOutputColum((int)$this->columnInOutTypes_[$i])){
                $ps->registerOutParameter($i + 1, (int)$this->columnTypes_[$i]);
            }
            if($this->isInputColum((int)$this->columnInOutTypes_[$i])){
                $ps->setObject($i + 1, $args[$argPos++], (int)$this->columnTypes_[$i]);
            }
        }
    }
    
    protected function isInputColum($columnInOutType){
        return $columnInOutType == S2Dao_DatabaseMetaData::procedureColumnIn ||
                $columnInOutType == S2Dao_DatabaseMetaData::procedureColumnInOut;
    }
    
    protected function isOutputColum($columnInOutType){
        return $columnInOutType == S2Dao_DatabaseMetaData::procedureColumnReturn ||
                $columnInOutType == S2Dao_DatabaseMetaData::procedureColumnOut ||
                $columnInOutType == S2Dao_DatabaseMetaData::procedureColumnInOut;
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
    
    protected function getValueType($clazz = null) {
        return S2Dao_PDOType::gettype(gettype($clazz));
    }
}

?>