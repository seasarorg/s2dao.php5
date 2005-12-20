<?php

/**
 * @author nowel
 */
class S2DaoBeanListReader extends S2DaoBeanReader {

    public function __construct($list, $dbMetaData) {
        $dbms = S2Dao_DbmsManager::getDbms($dbMetaData);
        $beanMetaData = new S2Dao_BeanMetaDataImpl(
                get_class($list->get(0)), $dbMetaData, $dbms);
        $this->setupColumns($beanMetaData);
        for ($i = 0; $i < $list->size(); ++$i) {
            $this->setupRow($beanMetaData, $list->get($i));
        }
    }

}
?>
