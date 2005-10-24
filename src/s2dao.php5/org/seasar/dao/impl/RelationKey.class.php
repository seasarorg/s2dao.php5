<?php

/**
 * @author Yusuke Hata
 */
final class RelationKey {

    private $values_ = array();
    private $hashCode_ = 0;
    
    public function __construct($values) {
        $this->values_ = $values;
        for ($i = 0; $i < count($values); ++$i) {
            // TO http://jp.php.net/manual/ja/function.crc32.php#52817
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
        if (!($o instanceof RelationKey)) {
            return false;
        }
        $otherValues = $o->values_;
        if (count($this->values_) != count($otherValues)) {
            return false;
        }
        for ($i = 0; $i < count($this->values_); ++$i) {
            if (!$this->values_[$i] === $otherValues[$i]) {
                return false;
            }
        }
        return true;
    }
}
?>
