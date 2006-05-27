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
                    $this->getDbms()->getSequenceNextValString($this->sequenceName_),
                    null);
            parent::setIdentifier($bean, $retVal);
        }
    }

    public function isSelfGenerate() {
        return $this->getDbms()->isSelfGenerate();
    }
}
?>
