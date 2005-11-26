<?php

class S2Dao_BeanArrayMetaDataResultSetHandler extends S2Dao_BeanListMetaDataResultSetHandler {

    public function __construct(S2Dao_BeanMetaData $beanMetaData) {
        parent::__construct($beanMetaData);
    }

    public function handle($rs){
        $list = parent::handle($rs);

        $array = array();
        foreach($list->toArray() as $value){
            $array[] = $value;
        }
       
        /*
        $array = new ArrayObject();
        foreach($list->toArray() as $value){
            $array->append($value);
        }
        */
        
        return $array;
    }
}
?>
