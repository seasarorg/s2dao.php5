<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractBasicProcedureHandler implements S2Dao_ProcedureHandler {

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

    protected function confirmProcedureName(PDO $conn, S2Dao_ProcedureMetaData $metadata) {
        $str = S2Dao_DatabaseMetaDataUtil::convertIdentifier($conn, $this->procedureName_);
        $names = explode('.', $str);
        $namesLength = count($names);
        
        $rs = array();
        if($namesLength == 1){
            $rs = $metadata->getProcedures(null, null, $names[0]);
        } else if($namesLength == 2){
            $rs = $metadata->getProcedures($names[0], null, $names[1]);
        } else if($namesLength == 3){
            $rs = $metadata->getProcedures($names[0], $names[1], $names[2]);
        }
        
        if(count($rs) == 0){
            throw new S2Container_S2RuntimeException('EDAO0012',
                                        array($this->procedureName_));
        } else if(count($rs) > 1){
            throw new S2Container_S2RuntimeException('EDAO0013',
                                        array($this->procedureName_));
        } else {
            return $rs[0];
        }

    }
    
    protected function initTypes(){
        $connection = $this->getConnection();
        $metadata = S2Dao_ProcedureMetaDataFactory::createProcedureMetaData($connection);
        $prcInfo = $this->confirmProcedureName($connection, $metadata);
        
        $buff = '';
        if($prcInfo['Type'] == S2Dao_ProcedureMetaData::STORED_PROCEDURE){
            $buff .= '{CALL ';
        } else {
            $buff .= 'SELECT ';
        }
        $buff .= $this->procedureName_;
        $buff .= '(';
        $columnNames = new S2Dao_ArrayList();
        $dataType = new S2Dao_ArrayList();
        $inOutTypes = new S2Dao_ArrayList();
        $outparameterNum = 0;
        $throughColumn = false;
        try {
            $inTypeColumn = $metadata->getProcedureColumnsIn($prcInfo);
            $outTypeColumn = $metadata->getProcedureColumnsOut($prcInfo);
            $inoutTypeColumn = $metadata->getProcedureColumnsInOut($prcInfo);
            
            foreach($inTypeColumn as $inType){
                $buff .= '?,';
                $columnNames->add($inType['name']);
                $dataType->add(array('in', $inType['type']));
                $throughColumn = true;
            }
            
            $merge = array_merge($outTypeColumn, $inoutTypeColumn);
            foreach($merge as $m){
                $buff .= '?,';
                $inOutTypes->add($m['type']);
                $dataType->add(array('out', $m['type']));
                $throughColumn = true;
            }
            
            if(!$throughColumn){
                throw new S2Container_S2RuntimeException('EDAO0010',
                                            array($this->procedureName_));
            }

            $buff = preg_replace('/(,$)/', '', $buff);
        } catch (Exception $e) {
            throw new S2Dao_SQLRuntimeException($e);
        }
        $buff .= ')';
        if($prcInfo['Type'] == S2Dao_ProcedureMetaData::STORED_PROCEDURE){
            $buff .= '}';
        }
        $this->sql_ = $buff;
        $this->columnNames_ = $columnNames->toArray();
        $this->columnTypes_ = $dataType->toArray();
        $this->columnInOutTypes_ = $inOutTypes->toArray();
        return $outparameterNum;
    }

    public function execute(array $args) {
        return $this->execute2($this->getConnection(), $args);
    }
    
    abstract protected function execute2(PDO $connection, array $args);
    
    // FIXME
    protected function bindArgs(PDOStatement $ps, array $args = null) {
        if ($args == null) {
            return;
        }
        
        for($i = 0; $i < count($args); $i++){
            $ps->bindValue($i + 1, $args[$i], $this->getValueType($args[$i]));
        }
        
        /*
        $argPos = 0;
        for ($i = 0; $i < count($this->columnTypes_); $i++) {
            if($this->isOutputColum($this->columnTypes_[$i])){
                $ps->bindParam($i + 1, $param, PDO::PARAM_INT|PDO::PARAM_INPUT_OUTPUT);
            }
            if($this->isInputColum($this->columnTypes_[$i])){
                $ps->bindValue($i + 1, $args[$argPos], $this->getValueType($args[$argPos]));
                $argPos++;
            }
        }
        */
    }

    // FIXME    
    protected function isInputColum(array $columnInOutType){
        return $columnInOutType[0] == 'in';
    }
    
    // FIXME
    protected function isOutputColum(array $columnInOutType){
        return $columnInOutType[0] == 'out';
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