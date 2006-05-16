<?php

/**
 * @author nowel
 */
class S2DaoBeanReader implements S2Dao_DataReader {

    private $dataSet_;
    private $table_;

    public function __construct(ReflectionClass $bean, PDO $connection) {
        $this->dataSet_ = new S2Dao_DataSetImpl();
        $this->table_ = $this->dataSet_->addTable('S2DaoBean');

        $dbms = S2Dao_DbmsManager::getDbms($connection);
        $beanMetaData = new S2Dao_BeanMetaDataImpl($bean, $connection, $dbms);
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
        $row->setState(RowStates::UNCHANGED);
    }

    public function read() {
        return $this->dataSet_;
    }

}
?>
