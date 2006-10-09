<?php

/**
 * @author nowel
 */
class S2Dao_ProcedureSqlWrapper extends S2Dao_SqlWrapperImpl {
    
    protected $parameterInOutTypes = array();

    protected $parameterTypes = array();

    public function __construct($sql,
                                array $parameterNames,
                                array $parameterTypes,
                                array $parameterInOutTypes) {
        parent::__construct($parameterNames, $sql, false);
        $this->parameterTypes = $parameterTypes;
        $this->parameterInOutTypes = $parameterInOutTypes;
    }
    
    public function getParameterTypes() {
        return $this->parameterTypes;
    }
    
    public function getParameterInOutTypes() {
        return $this->parameterInOutTypes;
    }
}

?>