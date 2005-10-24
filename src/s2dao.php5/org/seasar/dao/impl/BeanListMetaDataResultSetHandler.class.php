<?php

/**
 * @author Yusuke Hata
 */
class BeanListMetaDataResultSetHandler extends AbstractBeanMetaDataResultSetHandler {

    public function __construct(BeanMetaData $beanMetaData) {
        parent::__construct($beanMetaData);
    }

    public function handle($rs){
        $list = new ArrayList();
        while( $row = $rs->fetchRow(DB_FETCHMODE_OBJECT) ){
            $list->add($row);
        }
        
        $rs->free();
        
        return $list;
        
        // 当分省略 ;-P
        /*
        $columnNames = $this->createColumnNames($rs->getMetaData());
        $columnNames = array( "ID", "CONTENT", "TITLE" );
        $list = new ArrayList();
        $relSize = $this->getBeanMetaData()->getRelationPropertyTypeSize();
        $elRowCache = new RelationRowCache($relSize);
        
        while (rs.next()) {
            Object row = createRow(rs, columnNames);
            for (int i = 0; i < relSize; ++i) {
                RelationPropertyType rpt = getBeanMetaData()
                        .getRelationPropertyType(i);
                if (rpt == null) {
                    continue;
                }
                Object relRow = null;
                Map relKeyValues = new HashMap();
                RelationKey relKey = createRelationKey(rs, rpt, columnNames,
                        relKeyValues);
                if (relKey != null) {
                    relRow = relRowCache.getRelationRow(i, relKey);
                    if (relRow == null) {
                        relRow = createRelationRow(rs, rpt, columnNames,
                                relKeyValues);
                        relRowCache.addRelationRow(i, relKey, relRow);
                    }
                }
                if (relRow != null) {
                    PropertyDesc pd = rpt.getPropertyDesc();
                    pd.setValue(row, relRow);
                }
            }
            list.add(row);
        }
        return list;
        */
    }

    protected function createRelationKey($rs,
                                         RelationPropertyType $rpt,
                                         $columnNames,
                                         $relKeyValues){

        $keyList = new ArrayList();
        $bmd = $rpt->getBeanMetaData();
        for ($i = 0; $i < $rpt->getKeySize(); ++$i) {
            /*
            PropertyType pt = bmd
                    .getPropertyTypeByColumnName(rpt.getYourKey(i));
            ValueType valueType = pt.getValueType();
            String columnName = pt.getColumnName() + "_" + rpt.getRelationNo();
            */
            $valueType = null;
            $columnName = $rpt->getMyKey($i);
            if ($columnNames->contains($columnName)) {
                $pt = $this->getBeanMetaData()->getPropertyTypeByColumnName($columnName);
                $valueType = $pt->getValueType();
            } else {
                $pt = $bmd->getPropertyTypeByColumnName($rpt->getYourKey($i));
                $columnName = $pt->getColumnName() + "_" + $rpt->getRelationNo();
                if ($columnNames->contains($columnName)) {
                    $valueType = $pt->getValueType();
                } else {
                    return null;
                }
            }
            $value = $valueType->getValue($rs, $columnName);
            if ($value == null) {
                return null;
            }
            $relKeyValues->put($columnName, $value);
            $keyList->add($value);
        }
        if ($keyList->size() > 0) {
            $keys = $keyList->toArray();
            return new RelationKey($keys);
        } else {
            return null;
        }
    }
}
?>
