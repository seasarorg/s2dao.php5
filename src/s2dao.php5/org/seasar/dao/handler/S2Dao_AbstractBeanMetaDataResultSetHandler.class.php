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
 * @package org.seasar.s2dao.handler
 */
abstract class S2Dao_AbstractBeanMetaDataResultSetHandler implements S2Dao_ResultSetHandler {

    private $beanMetaData;
    protected $dbms;

    public function __construct(S2Dao_BeanMetaData $beanMetaData,
                                S2Dao_Dbms $dbms) {
        $this->beanMetaData = $beanMetaData;
        $this->dbms = $dbms;
    }

    /**
     * @return BeanMetaData
     */
    public function getBeanMetaData() {
        return $this->beanMetaData;
    }

    protected function createRow(array $rs) {
        $row = $this->beanMetaData->getBeanClass()->newInstance();
        $columnNames = new S2Dao_ArrayList(array_keys($rs));
        
        $size =  $this->beanMetaData->getPropertyTypeSize();
        for ($i = 0; $i < $size; ++$i) {
            $pt = $this->beanMetaData->getPropertyType($i);
            $ptColumnName = $pt->getColumnName();
            if ($columnNames->contains($ptColumnName)) {
                $valueType = $pt->getValueType();
                $value = $valueType->getValue($rs, $ptColumnName);
                $pd = $pt->getPropertyDesc();
                $pd->setValue($row, $value);
            } else if (!$pt->isPersistent()) {
                for ($iter = $columnNames->iterator(); $iter->valid(); $iter->next()) {
                    $columnName = $iter->current();
                    $columnName2 = str_replace('_', '', $columnName);
                    if (strcasecmp($columnName2, $ptColumnName) == 0) {
                        $valueType = $pt->getValueType();
                        $value = $valueType->getValue($rs, $columnName);
                        $pd = $pt->getPropertyDesc();
                        $pd->setValue($row, $value);
                        break;
                    }
                }
            }
        }
        return $row;
    }
    
    protected function createRelationRow() {
        $args = func_get_args();
        if(1 < func_num_args()){
            return $this->__call('createRelationRow0', $args);
        }
        return $this->__call('createRelationRow1', $args);
    }
    
    protected function createRelationRow0(array $rs,
                                         S2Dao_RelationPropertyType $rpt,
                                         S2Dao_Map $columnNames,
                                         S2Dao_Map $relKeyValues) {
        $row = null;
        $bmd = $rpt->getBeanMetaData();
        $size = $rpt->getKeySize();
        for ($i = 0; $i < $size; ++$i) {
            $columnName = $rpt->getMyKey($i);
            if ($columnNames->contains($columnName)) {
                if ($row === null) {
                    $row = $this->createRelationRow($rpt);
                }
                if ($relKeyValues !== null
                        && $relKeyValues->containsKey($columnName)) {
                    $value = $relKeyValues->get($columnName);
                    $pt = $bmd->getPropertyTypeByColumnName($rpt->getYourKey($i));
                    $pd = $pt->getPropertyDesc();
                    if ($value !== null) {
                        $pd->setValue($row, $value);
                    }
                }
            }
            continue;
        }
        $existColumn = 0;
        $propertyTypeSize = $bmd->getPropertyTypeSize();
        for ($i = 0; $i < $propertyTypeSize; ++$i) {
            $pt = $bmd->getPropertyType($i);
            $columnName = $pt->getColumnName() . '_' . $rpt->getRelationNo();
            if (!$columnNames->contains($columnName)) {
                continue;
            }
            $existColumn++;
            if ($row === null) {
                $row = $this->createRelationRow($rpt);
            }
            $value = null;
            if ($relKeyValues !== null && $relKeyValues->containsKey($columnName)) {
                $value = $relKeyValues->get($columnName);
            } else {
                $valueType = $pt->getValueType();
                $value = $valueType->getValue($rs, $columnName);
            }
            $pd = $pt->getPropertyDesc();
            if ($value !== null) {
                $pd->setValue($row, $value);
            }
        }
        if ($existColumn === 0) {
            return null;
        }
        return $row;
    }

    protected function createRelationRow1(S2Dao_RelationPropertyType $rpt) {
        return $rpt->getPropertyDesc()->getPropertyType()->newInstance();
    }
    
    protected function createColumnNames(array $rsmd) {
        $colNames = array_keys($rsmd);
        $count = count($colNames);
        $columnNames = new S2Dao_CaseInsensitiveMap();
        for ($i = 0; $i < $count; ++$i) {
            $columnNames->put($colNames);
        }
        return $columnNames;
    }
    
    private function __call($name, $args){
        if(method_exists($this, $name)){
            return call_user_func_array(array($this, $name), $args);
        }
    }

}
?>
