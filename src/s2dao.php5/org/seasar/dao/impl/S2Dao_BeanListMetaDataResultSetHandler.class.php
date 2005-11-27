<?php

/**
 * @author nowel
 */
class S2Dao_BeanListMetaDataResultSetHandler extends S2Dao_AbstractBeanMetaDataResultSetHandler {

    public function __construct(S2Dao_BeanMetaData $beanMetaData) {
        parent::__construct($beanMetaData);
    }

    public function handle($rs){
        $list = new S2Dao_ArrayList();

        $beanMetaData = $this->getBeanMetaData();
        while($row = $rs->fetch(PDO::FETCH_ASSOC)){
            $columns = array_keys($row);
            $cls = $beanMetaData->getBeanClass()->newInstance();
            
            foreach($row as $column => $value){
                $pt = $beanMetaData->getPropertyTypeByColumnName($column);

                if($pt === null){
                    continue;
                }
                
                if(in_array($pt->getColumnName(), $columns)){
                    $pd = $pt->getPropertyDesc();
                    $pd->setValue($cls, $value);
                } else if(!$pt->isPersistent()){
                }
            }
            $list->add($cls);
        }
        return $list;
        
//            $relSize = $this->getBeanMetaData()->getRelationPropertyTypeSize();
//            $elRowCache = new S2Dao_RelationRowCache($relSize);
//                
//                $rpt = $this->getBeanMetaData()->getRelationPropertyType($i);
//                
//                if($rpt === null){
//                    continue;
//                }
//                $relRow = null;
//                $relKeyValues = new S2Dao_HashMap();
//                $relKey = $this->createRelationKey($rpt, $column,
//                                                   $relKeyValues);
//                                                   
//                if ($relKey != null) {
//                    $relRow = $relRowCache->getRelationRow($i, $relKey);
//                    if ($relRow == null) {
//                        $relRow = $this->createRelationRow($row, $rpt, $column,
//                                                           $relKeyValues);
//                        $relRowCache->addRelationRow($i, $relKey, $relRow);
//                    }
//                }
//                if ($relRow != null) {
//                    $pd = $rpt->getPropertyDesc();
//                    $pd->setValue($row, $relRow);
//                }
    }

    protected function createRelationKey( S2Dao_RelationPropertyType $rpt,
                                             $columnNames,
                                             $relKeyValues){

//        $keyList = new S2Dao_ArrayList();
//        $bmd = $rpt->getBeanMetaData();
//        for ($i = 0; $i < $rpt->getKeySize(); ++$i) {
//            $pt = $bmd->getPropertyTypeByColumnName($rpt->getYourKey(i));
//            $valueType = $pt->getValueType();
//            $columnName = $pt->getColumnName() . "_" . $rpt->getRelationNo();
//            $valueType = null;
//            $columnName = $rpt->getMyKey($i);
//            if ($columnNames->contains($columnName)) {
//                $pt = $this->getBeanMetaData()->getPropertyTypeByColumnName($columnName);
//                $valueType = $pt->getValueType();
//            } else {
//                $pt = $bmd->getPropertyTypeByColumnName($rpt->getYourKey($i));
//                $columnName = $pt->getColumnName() + "_" + $rpt->getRelationNo();
//                if ($columnNames->contains($columnName)) {
//                    $valueType = $pt->getValueType();
//                } else {
//                    return null;
//                }
//            }
//            $value = $valueType->getValue($rs, $columnName);
//            if ($value == null) {
//                return null;
//            }
//            $relKeyValues->put($columnName, $value);
//            $keyList->add($value);
//        }
//        if ($keyList->size() > 0) {
//            $keys = $keyList->toArray();
//            return new S2Dao_RelationKey($keys);
//        } else {
//            return null;
//        }
    }
}
?>
