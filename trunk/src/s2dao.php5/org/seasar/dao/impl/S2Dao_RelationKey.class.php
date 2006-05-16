<?php

/**
 * @author nowel
 */
final class S2Dao_RelationKey {

    private $values_ = array();
    private $hashCode_ = 0;
    
    public function __construct($values) {
        $this->values_ = $values;
        $c = count($values);
        for ($i = 0; $i < $c; ++$i) {
            $this->hashCode_ += crc32($values[$i]);
        }
    }
    
    public function getValues() {
        return $this->values_;
    }
    
    public function hashCode() {
        return $this->hashCode_;
    }

    public function equals($o) {
        if (!($o instanceof S2Dao_RelationKey)) {
            return false;
        }
        
        $otherValues = $o->values_;
        if (count($this->values_) != count($otherValues)) {
            return false;
        }
        
        $c = count($this->values_);
        for ($i = 0; $i < $c; ++$i) {
            //if (!$this->values_[$i] === $otherValues[$i]) {
            if(!$otherValues[$i]->equals($this->values_[$i])){
                return false;
            }
        }
        return true;
    }
}
?>
