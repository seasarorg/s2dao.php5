<?php

/**
 * @author nowel
 */
interface S2Dao_ProcedureMetaData {
    
    const STORED_PROCEDURE = 'PROCEDURE';
    const STORED_FUNCTION = 'FUNCTION';
    
    public function getProcedures($catalog = null, $scheme = null, $procedureName);
    public function getProcedureColumnsIn(array $procedureInfo);
    public function getProcedureColumnsOut(array $procedureInfo);
    public function getProcedureColumnsInOut(array $procedureInfo);
    
}