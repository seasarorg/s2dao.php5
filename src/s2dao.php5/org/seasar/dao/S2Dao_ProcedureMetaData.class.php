<?php

/**
 * @author nowel
 */
interface S2Dao_ProcedureMetaData {
    
    const STORED_PROCEDURE = 'PROCEDURE';
    const STORED_FUNCTION = 'FUNCTION';
    
    const INTYPE = 0;
    const OUTTYPE = 1;
    const INOUTTYPE = 9;
    const RETURNTYPE = -1;
    
    public function getProcedures($catalog = null, $scheme = null, $procedureName);
    public function getProcedureColumnsIn(S2Dao_ProcedureInfo $procedureInfo);
    public function getProcedureColumnsOut(S2Dao_ProcedureInfo $procedureInfo);
    public function getProcedureColumnsInOut(S2Dao_ProcedureInfo $procedureInfo);
    public function getProcedureColumnReturn(S2Dao_ProcedureInfo $procedureInfo);
    
}