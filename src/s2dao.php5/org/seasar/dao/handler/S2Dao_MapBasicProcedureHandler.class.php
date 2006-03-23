<?php

/**
 * @author nowel
 */
class S2Dao_MapBasicProcedureHandler extends S2Dao_AbstractBasicProcedureHandler {

    public function __construct(S2Container_DataSource $ds,
                              $procedureName,
                              S2Dao_StatementFactory $statementFactory = null){
        if($statementFactory === null){
            $statementFactory = new S2Dao_BasicStatementFactory;
        }
        $this->setDataSource($ds);
        $this->setProcedureName($procedureName);
        $this->setStatementFactory($statementFactory);
        $this->initTypes();
    }
    
    protected function execute(PDO $connection, array $args){
        $stmt = null;
        try {
            $stmt = $this->prepareCallableStatement($connection);
            $this->bindArgs($stmt, $args);
            $stmt->execute();
            $result = new S2Dao_HashMap();
            
            $c = count($this->columnInOutTypes_);
            for ($i = 0; $i < $c; $i++) {
                if($this->isOutputColum((int)$this->columnInOutTypes_[$i])){
                    $result->put($this->columnNames_[$i],
                                 $stmt->getObject($i + 1));
                }
            }
            return $result;
        } catch (S2Container_SQLException $e) {
            throw new S2Container_SQLRuntimeException($e);
        }
    }

}
?>