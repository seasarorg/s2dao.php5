<?php

/**
 * @author nowel
 */
class S2Dao_SqlWrapperImpl implements S2Dao_SqlWrapper {
    
    private $batch;
    private $parameterNames = array();
    private $sql;
    
    public function __construct(array $parameterNames, $sql, $batch = false) {
        $this->parameterNames = $parameterNames;
        $this->sql = $sql;
        $this->batch = $batch;
    }
    
    public function isBatch() {
        return $this->batch;
    }
    
    public function getParameterNames() {
        return $this->parameterNames;
    }
    
    public function getSql() {
        return $this->sql;
    }
    
    public function preUpdateBean(S2Dao_CommandContext $ctx) {
    }

    public function postUpdateBean(S2Dao_CommandContext $ctx, $returnValue) {
    }
    
    public function transformSql($sql) {
        return $sql;
    }
    
}

?>