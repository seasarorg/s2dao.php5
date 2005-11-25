<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractBeanMetaDataResultSetHandler implements S2Container_ResultSetHandler {

    private $beanMetaData_;

    public function __construct(S2Dao_BeanMetaData $beanMetaData) {
        $this->beanMetaData_ = $beanMetaData;
    }

    public function getBeanMetaData() {
        return $this->beanMetaData_;
    }

    protected function createRow($columnNames){
//        $row = S2Container_ClassUtil::newInstance($this->beanMetaData_->getBeanClass());
//        for ($i = 0; $i < $this->beanMetaData_->getPropertyTypeSize(); ++$i) {
//            $pt = $this->beanMetaData_->getPropertyType($i);
//            
//            if( $pt === null ){
//                continue;
//            }
//            
//            if ($columnNames->contains($pt->getColumnName())) {
//                $valueType = $pt->getValueType();
//                $value = $valueType->getValue($rs, $pt->getColumnName());
//                $pd = $pt->getPropertyDesc();
//                $pd->setValue($row, $value);
//            } else if (!$pt->isPersistent()) {
//                for ($iter = $columnNames->getIterator(); $iter->valid(); $iter->next()) {
//                    $columnName = $iter->current();
//                    $columnName2 = str_replace("_", "", $columnName);
//                    if (strcasecmp($columnName2, $pt->getColumnName()) == 0 ) {
//                        $valueType = $pt->getValueType();
//                        $value = $valueType->getValue($rs, $columnName);
//                        $pd = $pt->getPropertyDesc();
//                        $pd->setValue($row, $value);
//                        break;
//                    }
//                }
//            }
//        }
//        return $row;
    }

    protected function createRelationRow($rs,
                                         S2Dao_RelationPropertyType $rpt,
                                         $columnNames,
                                         $relKeyValues){

//        $row = null;
//        $bmd = $rpt->getBeanMetaData();
//        for ($i = 0; $i < $rpt->getKeySize(); ++$i) {
//            $columnName = $rpt->getMyKey($i);
//            if ($columnNames->contains($columnName)) {
//                if ($row == null) {
//                    $row = S2Container_ClassUtil::newInstance($rpt->getPropertyDesc()->getPropertyType());
//                }
//                if ($relKeyValues != null && $relKeyValues->containsKey($columnName)) {
//                    $value = $relKeyValues->get($columnName);
//                    $pt = $bmd->getPropertyTypeByColumnName($rpt->getYourKey($i));
//                    $pd = $pt->getPropertyDesc();
//                    if ($value != null) {
//                        $pd->setValue($row, $value);
//                    }
//                }
//            }
//            continue;
//        }
//        for ($i = 0; $i < $bmd->getPropertyTypeSize(); ++$i) {
//            $pt = $bmd->getPropertyType($i);
//            $columnName = $pt->getColumnName() . "_" . $rpt->getRelationNo();
//            if (!$columnNames->contains($columnName)) {
//                continue;
//            }
//            if ($row == null) {
//                $row = ClassUtil::newInstance($rpt->getPropertyDesc()->getPropertyType());
//            }
//            $value = null;
//            if ($relKeyValues != null && $relKeyValues->containsKey($columnName)) {
//                $value = $relKeyValues->get($columnName);
//            } else {
//                $valueType = $pt->getValueType();
//                $value = $valueType->getValue($rs, $columnName);
//            }
//            $pd = $pt->getPropertyDesc();
//            if ($value != null) {
//                $pd->setValue($row, $value);
//            }
//
//        }
//        return $row;
    }

}
?>
