<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2007 the Seasar Foundation and the Others.            |
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
 * @package org.seasar.s2dao.handler
 */
class S2Dao_ArrayRelationPropertyHandler implements S2Dao_RelationPropertyHandler {
    
    private $beanMetaData;
    private $rpt;
    protected $command;
    
    public function __construct(S2Dao_BeanMetaData $beanMetaData,
                                S2Dao_RelationPropertyType $rpt,
                                S2Dao_SelectDynamicCommand $command) {
        $this->beanMetaData = $beanMetaData;
        $this->rpt = $rpt;
        $this->command = $command;
    }
    
    public function setupRelationProperty($row) {
        $relationRow = $this->command->execute(
                            $this->createKey($this->beanMetaData, $this->rpt, $row));
        if ($relationRow !== null) {
            $pd = $rpt->getPropertyDesc();
            $pd->setValue($row, $relationRow);
        }        
    }
    
    private function createKey(S2Dao_BeanMetaData $beanMetaData,
                               S2Dao_RelationPropertyType $relationPropertyType,
                               $target) {
        $key = array();
        $keySize = $relationPropertyType->getKeySize();
        for ($i = 0; $i < $keySize; ++$i) {
            $pkName = $relationPropertyType->getMyKey($i);
            $propertyType = $this->beanMetaData->getPropertyTypeByColumnName($pkName);
            $key[$i] = $propertyType->getPropertyDesc()->getValue($target);
        }
        return $key;
    }

}
