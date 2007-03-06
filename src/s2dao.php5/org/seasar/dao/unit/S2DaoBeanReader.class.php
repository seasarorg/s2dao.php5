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
// $Id$
//
/**
 * @author nowel
 */
class S2DaoBeanReader implements S2Dao_DataReader {

    protected $dataSet_;
    protected $table_;

    public function __construct($bean, PDO $connection) {
        $this->dataSet_ = new S2Dao_DataSetImpl();
        $this->table_ = $this->dataSet_->addTable('S2DaoBean');

        $dbms = S2Dao_DbmsManager::getDbms($connection);
        $clazz = new ReflectionClass($bean);
        $beanMetaData = new S2Dao_BeanMetaDataImpl($clazz, $connection, $dbms);
        $this->setupColumns($beanMetaData);
        $this->setupRow($beanMetaData, $bean);
    }

    protected function setupColumns(S2Dao_BeanMetaData $beanMetaData) {
        for ($i = 0; $i < $beanMetaData->getPropertyTypeSize(); ++$i) {
            $pt = $beanMetaData->getPropertyType($i);
            $propertyType = $pt->getPropertyDesc()->getPropertyType();
            $this->table_->addColumn($pt->getColumnName(),
                                     S2Dao_ColumnTypes::getColumnType($propertyType));
        }
        for ($i = 0; $i < $beanMetaData->getRelationPropertyTypeSize(); ++$i) {
            $rpt = $beanMetaData->getRelationPropertyType($i);
            for ($j = 0; $j < $rpt->getBeanMetaData()->getPropertyTypeSize(); $j++) {
                $pt = $rpt->getBeanMetaData()->getPropertyType($j);
                $columnName = $pt->getColumnName() . '_' . $rpt->getRelationNo();
                $propertyType = $pt->getPropertyDesc()->getPropertyType();
                $this->table_->addColumn($columnName,
                                    S2Dao_ColumnTypes::getColumnType($propertyType));
            }
        }
    }

    protected function setupRow(S2Dao_BeanMetaData $beanMetaData, $bean) {
        $row = $this->table_->addRow();
        for ($i = 0; $i < $beanMetaData->getPropertyTypeSize(); ++$i) {
            $pt = $beanMetaData->getPropertyType($i);
            $pd = $pt->getPropertyDesc();
            $value = $pd->getValue($bean);
            $ct = S2Dao_ColumnTypes::getColumnType($pd->getPropertyType());
            $row->setValue($pt->getColumnName(), $ct->convert($value, null));
        }
        for ($i = 0; $i < $beanMetaData->getRelationPropertyTypeSize(); ++$i) {
            $rpt = $beanMetaData->getRelationPropertyType($i);
            $relationBean = $rpt->getPropertyDesc()->getValue($bean);
            if ($relationBean === null) {
                continue;
            }
            for ($j = 0; $j < $rpt->getBeanMetaData()->getPropertyTypeSize(); $j++) {
                $pt = $rpt->getBeanMetaData()->getPropertyType($j);
                $columnName = $pt->getColumnName() . '_' . $rpt->getRelationNo();
                $pd = $pt->getPropertyDesc();
                $value = $pd->getValue($relationBean);
                $ct = S2Dao_ColumnTypes::getColumnType($pd->getPropertyType());
                $row->setValue($columnName, $ct->convert($value, null));
            }
        }
        $row->setState(S2Dao_RowStates::UNCHANGED);
    }

    public function read() {
        return $this->dataSet_;
    }

    public function getTable(){
        return $this->table_;
    }

}
?>
