<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright 2005-2006 the Seasar Foundation and the Others.            |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the "License");      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// |                                                                      |
// |     http://www.apache.org/licenses/LICENSE-2.0                       |
// |                                                                      |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an "AS IS" BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,                        |
// | either express or implied. See the License for the specific language |
// | governing permissions and limitations under the License.             |
// +----------------------------------------------------------------------+
// | Authors: nowel                                                       |
// +----------------------------------------------------------------------+
// $Id$
//
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
