<?php

/**
 * @author nowel
 */
class S2Dao_AssignedIdentifierGenerator extends S2Dao_AbstractIdentifierGenerator {

    public function __construct($propertyName, S2Dao_Dbms $dbms) {
        parent::__construct($propertyName, $dbms);
    }

    public function setIdentifier($bean, $value) {
    }

    public function isSelfGenerate() {
        return true;
    }
}
?>
