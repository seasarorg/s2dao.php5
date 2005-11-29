<?php

/**
 * @author nowel
 */
class S2Dao_BeanMetaDataResultSetHandler extends S2Dao_AbstractBeanMetaDataResultSetHandler {

    public function __construct(S2Dao_BeanMetaData $beanMetaData) {
        parent::__construct($beanMetaData);
    }

    public function handle($resultSet){
        $rows = $resultSet->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = count($rows);
        if($rowCount < 1){
            return null;
        }

        $clazz = array();
        $beanMetaData = $this->getBeanMetaData();
        for($i = 0; $i < $rowCount; $i++){
            $columns = array_keys($rows[$i]);
            $claz = $beanMetaData->getBeanClass()->newInstance();
 
            foreach($rows[$i] as $column => $value){
                $pt = $beanMetaData->getPropertyTypeByColumnName($column);
                if ($pt == null) {
                    continue;
                }
                if (in_array($pt->getColumnName(), $columns)) {
                    $pd = $pt->getPropertyDesc();
                    $pd->setValue($claz, $value);
                }
            }
            $clazz[] = $claz;
        }
        return $clazz;
    }
}
?>
