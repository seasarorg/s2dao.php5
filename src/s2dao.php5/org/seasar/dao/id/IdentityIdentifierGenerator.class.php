<?php

/**
 * @author Yusuke Hata
 */
class IdentityIdentifierGenerator extends AbstractIdentifierGenerator {

    public function __construct($propertyName, Dbms $dbms) {
        parent::__construct($propertyName, $dbms);
    }

    /**
     * @param $bean
     * @param $ds type.DataSource
     */
//    public function setIdentifier($bean, $value) {
//        if( $value instanceof DataSource){
//            $val = $this->executeSql($value,
//                        $this->getDbms()->getIdentitySelectString(),
//                        null);
//            $this->setIdentifier($bean, $val);
//        }
//    }

    public function isSelfGenerate() {
        return false;
    }
}
?>
