<?php

/**
 * @author nowel
 */
class S2Dao_IdentityIdentifierGenerator extends S2Dao_AbstractIdentifierGenerator {

    public function __construct($propertyName, S2Dao_Dbms $dbms) {
        parent::__construct($propertyName, $dbms);
    }

    public function setIdentifier($bean, $value) {
        if($value instanceof S2Container_PDODataSource){
            $retVal = $this->executeSql($value,
                                     $this->getDbms()->getIdentitySelectString(),
                                     null);
            $this->setIdentifier($bean, $retVal);
        }
    }

    public function isSelfGenerate() {
        return false;
    }
}
?>
