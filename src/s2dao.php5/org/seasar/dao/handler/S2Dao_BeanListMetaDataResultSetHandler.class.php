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
class S2Dao_BeanListMetaDataResultSetHandler
    extends S2Dao_AbstractBeanMetaDataResultSetHandler {
    
    /**
     * @var S2Dao_RelationPropertyHandler
     */    
    private $relationPropertyHandlers = array();

    public function __construct(S2Dao_BeanMetaData $beanMetaData,
                                S2Dao_Dbms $dbms,
                                array $relationPropertyHandlers) {
        parent::__construct($beanMetaData, $dbms);
        $this->relationPropertyHandlers = $relationPropertyHandlers;
    }
    
    public function handle(PDOStatement $rs){
        $list = new S2Dao_ArrayList();
        $bmd = $this->getBeanMetaData();
        $relSize = $bmd->getRelationPropertyTypeSize();
        $relRowCache = new S2Dao_RelationRowCache($relSize);
        while($result = $rs->fetch(PDO::FETCH_ASSOC)){
            $row = $this->createRow($result);
            for($i = 0; $i < $relSize; $i++){
                $rpt = $bmd->getRelationPropertyType($i);
                if ($rpt === null) {
                    continue;
                }

                $relRow = null;
                $relKeyValues = new S2Dao_HashMap();
                $relKey = $this->createRelationKey($rpt, $result, $relKeyValues);
                if ($relKey !== null) {
                    $relRow = $relRowCache->getRelationRow($i, $relKey);
                    if ($relRow === null) {
                        $relRow = $this->createRelationRow($rpt, $result, $relKeyValues);
                        $relRowCache->addRelationRow($i, $relKey, $relRow);
                    }
                }
                if ($relRow !== null) {
                    $pd = $rpt->getPropertyDesc();
                    $pd->setValue($row, $relRow);
                }
            }
            $c = count($this->relationPropertyHandlers);
            for ($i = 0; $i < $c; $i++) {
                $this->relationPropertyHandlers[$i]->setupRelationProperty($row);
            }
            $list->add($row);
        }
        return $list;
    }

    protected function createRelationKey(S2Dao_RelationPropertyType $rpt,
                                         array $resultSet,
                                         S2Dao_Map $relKeyValues){

        $keyList = new S2Dao_ArrayList();
        $columnNames = new S2Dao_ArrayList(array_keys($resultSet));
        $bmd = $rpt->getBeanMetaData();
        for ($i = 0; $i < $rpt->getKeySize(); ++$i) {
            $valueType = null;
            $columnName = $rpt->getMyKey($i);
            if ($columnNames->contains($columnName)) {
                $pt = $bmd->getPropertyTypeByColumnName($columnName);
                $valueType = $pt->getValueType();
            } else {
                $pt = $bmd->getPropertyTypeByColumnName($rpt->getYourKey($i));
                $columnName = $pt->getColumnName() . '_' . $rpt->getRelationNo();
                if ($columnNames->contains($columnName)) {
                    $valueType = $pt->getValueType();
                } else {
                    return null;
                }
            }
            $value = $valueType->getValue($resultSet, $columnName);
            if ($value === null) {
                return null;
            }
            $relKeyValues->put($columnName, $value);
            $keyList->add($value);
        }
        if (0 < $keyList->size()) {
            return new S2Dao_RelationKey($keyList->toArray());
        }
        return null;
    }
}
?>
