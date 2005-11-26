<?php

/**
 * @author nowel
 */
class S2Dao_BeanMetaDataResultSetHandler extends S2Dao_AbstractBeanMetaDataResultSetHandler {

    public function __construct(S2Dao_BeanMetaData $beanMetaData) {
        parent::__construct($beanMetaData);
    }

    public function handle($resultSet){
        if ($resultSet->next()) {
            $columnNames = $this->createColumnNames($resultSet->getMetaData());
            $row = $this->createRow($resultSet, $columnNames);
            for ($i = 0; $i < $this->getBeanMetaData()->getRelationPropertyTypeSize(); ++$i) {
                $rpt = $this->getBeanMetaData()->getRelationPropertyType($i);
                if ($rpt == null) {
                    continue;
                }
                $relationRow = $this->createRelationRow($resultSet, $rpt, $columnNames, null);
                if ($relationRow != null) {
                    $pd = $rpt->getPropertyDesc();
                    $pd->setValue($row, $relationRow);
                }
            }
            return $row;
        } else {
            return null;
        }
    }
}
?>
