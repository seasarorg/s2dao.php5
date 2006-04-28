<?php

/**
 * @author nowel
 */
class S2Dao_SequenceIdentifierGenerator extends S2Dao_AbstractIdentifierGenerator {

    private $sequenceName_;

    public function __construct($propertyName, S2Dao_Dbms $dbms) {
        parent::__construct($propertyName, $dbms);
    }

    public function getSequenceName() {
        return $this->sequenceName_;
    }

    public function setSequenceName($sequenceName) {
        $this->sequenceName_ = $sequenceName;
    }

    public function setIdentifier($bean, $value) {
        if($value instanceof S2Container_PDODataSource){
            $retVal = $this->executeSql($value,
                    $this->getDbms()->getSequenceNextValString(
                    $this->sequenceName_),null);
            $this->setIdentifier($bean, $retVal);
        }
    }

    public function isSelfGenerate() {
        return true;
    }
}
?>
