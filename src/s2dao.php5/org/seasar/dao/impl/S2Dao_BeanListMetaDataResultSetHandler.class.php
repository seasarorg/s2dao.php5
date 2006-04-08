<?php

/**
 * @author nowel
 */
class S2Dao_BeanListMetaDataResultSetHandler
    extends S2Dao_AbstractBeanMetaDataResultSetHandler {

    public function __construct(S2Dao_BeanMetaData $beanMetaData) {
        parent::__construct($beanMetaData);
    }

    public function handle($rs){
        $list = new S2Dao_ArrayList();
        $relSize = $this->getBeanMetaData()->getRelationPropertyTypeSize();
        $relRowCache = new S2Dao_RelationRowCache($relSize);

        while($result = $rs->fetch(PDO::FETCH_ASSOC)){
            $row = $this->createRow($result);
            for($i = 0; $i < $relSize; $i++){
                $rpt = $this->getBeanMetaData()->getRelationPropertyType($i);
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
            $list->add($row);
        }
        return $list;
    }

    protected function createRelationKey(S2Dao_RelationPropertyType $rpt,
                                         array $resultSet,
                                         S2Dao_HashMap $relKeyValues){

        $keyList = new S2Dao_ArrayList();
        $columnNames = new S2Dao_ArrayList(array_keys($resultSet));
        $bmd = $rpt->getBeanMetaData();
        for ($i = 0; $i < $rpt->getKeySize(); ++$i) {
            $valueType = null;
            $columnName = $rpt->getMyKey($i);
            if ($columnNames->contains($columnName)) {
                $pt = $this->getBeanMetaData()->getPropertyTypeByColumnName($columnName);
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
            if(!isset($resultSet[$columnName])){
                return null;
            }
            $value = $resultSet[$columnName];
            $relKeyValues->put($columnName, $value);
            $keyList->add($value);
        }
        if ($keyList->size() > 0) {
            $keys = $keyList->toArray();
            return new S2Dao_RelationKey($keys);
        }
        return null;
    }
}
?>
