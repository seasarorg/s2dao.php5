<?php

/**
 * @author nowel
 */
class IdentityIdentifierGenerator extends AbstractIdentifierGenerator {

    public function __construct($propertyName, Dbms $dbms) {
        parent::__construct($propertyName, $dbms);
    }

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
