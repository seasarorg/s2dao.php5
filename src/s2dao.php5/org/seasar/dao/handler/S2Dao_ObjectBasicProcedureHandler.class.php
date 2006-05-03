<?php

/**
 * @author nowel
 */
class S2Dao_ObjectBasicProcedureHandler extends S2Dao_AbstractBasicProcedureHandler {

    public function __construct(S2Container_DataSource $ds,
                               $procedureName,
                               S2Dao_StatementFactory $statementFactory = null){
        if($statementFactory === null){
            $statementFactory = new S2Dao_BasicStatementFactory;
        }
		$this->setDataSource($ds);
		$this->setProcedureName($procedureName);
		$this->setStatementFactory($statementFactory);
		if(1 < $this->initTypes()){
			throw new S2Container_S2RuntimeException('EDAO0010');
		}
	}
    
	protected function execute2(PDO $connection, array $args){
		try {
			$stmt = $this->prepareCallableStatement($connection);
			$this->bindArgs($stmt, $args);
			$stmt->execute();
            $c = count($this->columnInOutTypes_);
			for ($i = 0; $i < $c; $i++) {
				//if($this->isOutputColum($this->columnTypes_[$i])){
                    $row = $stmt->fetch(PDO::FETCH_NUM, $i + 1);
                    return $row[0];
				//}
			}
			return null;
		} catch (Exception $e) {
			throw new S2Dao_SQLRuntimeException($e);
		}
	}
}
?>