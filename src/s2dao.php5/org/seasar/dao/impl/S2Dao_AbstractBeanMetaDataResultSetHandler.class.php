<?php

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
                for ($iter = $columnNames->iterator(); $iter->valid(); $iter->next()) {
                    $columnName = $iter->current();
                    $columnName2 = str_replace("_", "", $columnName);
                    if (strcasecmp($columnName2, $pt->getColumnName()) == 0 ) {
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
                                         array $resultSet,
                                         S2Dao_HashMap $relKeyValues){

        $row = null;
        $columnNames = new S2Dao_ArrayList(array_keys($resultSet));
        $bmd = $rpt->getBeanMetaData();
        for ($i = 0; $i < $rpt->getKeySize(); ++$i) {
            $columnName = $rpt->getMyKey($i);
            if ($columnNames->contains($columnName)) {
                if ($row === null) {
                    $row = $bmd->getBeanClass()->newInstance();
                }
                if ($relKeyValues != null && $relKeyValues->containsKey($columnName)) {
                    $value = $relKeyValues->get($columnName);
                    $pt = $bmd->getPropertyTypeByColumnName($rpt->getYourKey($i));
                    $pd = $pt->getPropertyDesc();
                    if ($value != null) {
                        $pd->setValue($row, $value);
                    }
                }
            }
            continue;
        }
        for ($i = 0; $i < $bmd->getPropertyTypeSize(); ++$i) {
            $pt = $bmd->getPropertyType($i);
            $columnName = $pt->getColumnName() . "_" . $rpt->getRelationNo();
            if (!$columnNames->contains($columnName)) {
                continue;
            }
            if ($row === null) {
                $row = $bmd->getBeanClass()->newInstance();
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
