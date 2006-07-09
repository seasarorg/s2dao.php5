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
// $Id$
//
/**
 * @author nowel
 */
abstract class S2Dao_AbstractBeanMetaDataResultSetHandler implements S2Dao_ResultSetHandler {

    private $beanMetaData_;

    public function __construct(S2Dao_BeanMetaData $beanMetaData) {
        $this->beanMetaData_ = $beanMetaData;
    }

    public function getBeanMetaData() {
        return $this->beanMetaData_;
    }

    protected function createRow(array $resultSet){
        $row = $this->beanMetaData_->getBeanClass()->newInstance();
        $columnNames = new S2Dao_ArrayList(array_keys($resultSet));
        
        $c = $this->beanMetaData_->getPropertyTypeSize();
        for($i = 0; $i < $c; ++$i) {
            $pt = $this->beanMetaData_->getPropertyType($i);
            if ($columnNames->contains($pt->getColumnName())) {
                $value = $resultSet[$pt->getColumnName()];
                $pd = $pt->getPropertyDesc();
                $pd->setValue($row, $value);
            } else if (!$pt->isPersistent()) {
                $iter = $columnNames->iterator();
                for (; $iter->valid(); $iter->next()) {
                    $columnName = $iter->current();
                    $columnName2 = str_replace('_', '', $columnName);
                    if (strcasecmp($columnName2, $pt->getColumnName()) == 0) {
                        $value = $resultSet[$pt->getColumnName()];
                        $pd = $pt->getPropertyDesc();
                        $pd->setValue($row, $value);
                        break;
                    }
                }
            }
        }
        return $row;
    }

    protected function createRelationRow(S2Dao_RelationPropertyType $rpt,
                                         array $resultSet = null,
                                         S2Dao_HashMap $relKeyValues = null){

        if($resultSet == null && $relKeyValues == null){
            return $rpt->getPropertyDesc()->getPropertyType()->newInstance();
        }

        $row = null;
        $columnNames = new S2Dao_ArrayList(array_keys($resultSet));
        $bmd = $rpt->getBeanMetaData();
        for ($i = 0; $i < $rpt->getKeySize(); ++$i) {
            $columnName = $rpt->getMyKey($i);
            if ($columnNames->contains($columnName)) {
                if ($row === null) {
                    $row = $this->createRelationRow($rpt);
                }
                if ($relKeyValues != null && $relKeyValues->containsKey($columnName)) {
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
        $c = $bmd->getPropertyTypeSize();
        for ($i = 0; $i < $c; ++$i) {
            $pt = $bmd->getPropertyType($i);
            $columnName = $pt->getColumnName() . '_' . $rpt->getRelationNo();
            if (!$columnNames->contains($columnName)) {
                continue;
            }
            if ($row === null) {
                $row = $this->createRelationRow($rpt);
            }
            $value = null;
            if ($relKeyValues != null && $relKeyValues->containsKey($columnName)) {
                $value = $relKeyValues->get($columnName);
            } else {
                $value = $resultSet[$columnName];
            }
            $pd = $pt->getPropertyDesc();
            if ($value != null) {
                $pd->setValue($row, $value);
            }
        }
        return $row;
    }

}
?>
