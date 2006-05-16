<?php

/**
 * @author nowel
 */
class S2Dao_BeanArrayMetaDataResultSetHandler extends S2Dao_BeanListMetaDataResultSetHandler {

    public function __construct(S2Dao_BeanMetaData $beanMetaData) {
        parent::__construct($beanMetaData);
    }

    public function handle($rs){
        return parent::handle($rs)->toArray();
    }
}
?>
