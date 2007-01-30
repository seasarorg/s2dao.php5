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