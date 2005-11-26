<?php

/**
 * @author nowel
 */
class S2Dao_AssignedIdentifierGenerator extends S2Dao_AbstractIdentifierGenerator {

    public function __construct($propertyName, S2Dao_Dbms $dbms) {
        parent::__construct($propertyName, $dbms);
    }

    public function setIdentifier($bean, S2Container_DataSource $ds) {
    }

    public function isSelfGenerate() {
        return true;
    }
}
?>
