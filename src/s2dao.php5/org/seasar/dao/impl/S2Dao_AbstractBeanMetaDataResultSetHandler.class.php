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
        foreach($resultSet as $column => $value){
            $pt = $this->beanMetaData_->getPropertyTypeByColumnName($column);
            
            if( $pt === null ){
                continue;
            }

            if ($columnNames->contains($pt->getColumnName())) {
                $pd = $pt->getPropertyDesc();
                $pd->setValue($row, $value);
            } else if (!$pt->isPersistent()) {
                for ($iter = $columnNames->iterator(); $iter->valid(); $iter->next()) {
                    $columnName = $iter->current();
                    $columnName2 = str_replace("_", "", $columnName);
                    if (strcasecmp($columnName2, $pt->getColumnName()) == 0 ) {
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
                if ($row == null) {
                    $row = S2Container_ClassUtil::newInstance($rpt->getPropertyDesc()->getPropertyType());
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
            if ($row == null) {
                $row = ClassUtil::newInstance($rpt->getPropertyDesc()->getPropertyType());
            }
            $value = null;
            if ($relKeyValues != null && $relKeyValues->containsKey($columnName)) {
                $value = $relKeyValues->get($columnName);
            } else {
                $valueType = $pt->getValueType();
                $value = $valueType->getValue($rs, $columnName);
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
