<?php

class BeanArrayMetaDataResultSetHandler extends BeanListMetaDataResultSetHandler {

    public function __construct(BeanMetaData $beanMetaData) {
        parent::__construct($beanMetaData);
    }

    public function handle($rs){
        $list = parent::handle($rs);
        
        $array = new ArrayObject();
        foreach( $list->toArray() as $value ){
            $array->append($value);
        }
        
        return $array;
    }
}
?>
