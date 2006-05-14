<?php

/**
 * @author nowel
 */
class S2Dao_BeanMetaDataResultSetHandler extends S2Dao_AbstractBeanMetaDataResultSetHandler {

    public function __construct(S2Dao_BeanMetaData $beanMetaData) {
        parent::__construct($beanMetaData);
    }

    public function handle($resultSet){
        $row = null;
        while($result = $resultSet->fetch(PDO::FETCH_ASSOC)){
            $row = $this->createRow($result);
            $beanMetaData = $this->getBeanMetaData();
            $size = $beanMetaData->getRelationPropertyTypeSize();
            
            for($i = 0; $i < $size; $i++){
                $rpt = $beanMetaData->getRelationPropertyType($i);
                if($rpt === null){
                    continue;
                }

                $relationRow = $this->createRelationRow($rpt, $result, new S2Dao_HashMap);
                if ($relationRow !== null) {
                    $pd = $rpt->getPropertyDesc();
                    $pd->setValue($row, $relationRow);
                }
            }
        }
        return $row;
    }
}
?>
