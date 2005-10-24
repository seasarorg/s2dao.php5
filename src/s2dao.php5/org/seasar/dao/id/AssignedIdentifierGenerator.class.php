<?php

/**
 * @author Yusuke Hata
 */
class AssignedIdentifierGenerator extends AbstractIdentifierGenerator {

    public function __construct($propertyName, Dbms $dbms) {
        parent::__construct($propertyName, $dbms);
    }

    public function setIdentifier($bean, DataSource $ds) {
    }

    public function isSelfGenerate() {
        return true;
    }
}
?>
