<?php

/**
 * @author nowel
 */
class BeanListMetaDataResultSetHandler extends AbstractBeanMetaDataResultSetHandler {
    
    const FETCH = DB_FETCHMODE_ARRAY;

    public function __construct(BeanMetaData $beanMetaData) {
        parent::__construct($beanMetaData);
    }

    public function handle($rs){
        $list = new ArrayList();

        while($row = $rs->fetchRow(self::FETCH)){
            $columns = array_keys($row);
            $cls = $this->getBeanMetaData()->getBeanClass()->newInstance();
            
            for($i = 0; $i < count($columns); $i++){
                $value = $row[$i];
                $pt = $this->getBeanMetaData()->getPropertyType($i);

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
        $rs->free();
        return $list;
        
//            $relSize = $this->getBeanMetaData()->getRelationPropertyTypeSize();
//            $elRowCache = new RelationRowCache($relSize);
//                
//                $rpt = $this->getBeanMetaData()->getRelationPropertyType($i);
//                
//                if($rpt === null){
//                    continue;
//                }
//                $relRow = null;
//                $relKeyValues = new HashMap();
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

    protected function createRelationKey( RelationPropertyType $rpt,
                                             $columnNames,
                                             $relKeyValues){

//        $keyList = new ArrayList();
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
//            return new RelationKey($keys);
//        } else {
//            return null;
//        }
    }
}
?>
