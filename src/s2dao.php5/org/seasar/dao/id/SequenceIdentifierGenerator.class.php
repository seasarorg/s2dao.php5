<?php

/**
 * @author Yusuke Hata
 */
class SequenceIdentifierGenerator extends AbstractIdentifierGenerator {

    private $sequenceName_;

    public function __construct($propertyName, Dbms $dbms) {
        parent::__construct($propertyName, $dbms);
    }

    public function getSequenceName() {
        return $this->sequenceName_;
    }

    public function setSequenceName($sequenceName) {
        $this->sequenceName_ = $sequenceName;
    }

    public function setIdentifier($bean, DataSource $ds) {
        $value = $this->executeSql($ds, $this->getDbms()->getSequenceNextValString($this->sequenceName_), null);
        $this->setIdentifier($bean, $value);
    }

    public function isSelfGenerate() {
        return true;
    }
}
?>
