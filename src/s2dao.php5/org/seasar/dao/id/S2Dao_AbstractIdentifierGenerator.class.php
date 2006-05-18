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
abstract class S2Dao_AbstractIdentifierGenerator implements S2Dao_IdentifierGenerator {

    private static $resultSetHandler_;
    private $propertyName_;
    private $dbms_;
    
    public function __construct($propertyName, S2Dao_Dbms $dbms) {
        self::$resultSetHandler_ = new S2Dao_ObjectResultSetHandler();
        $this->propertyName_ = $propertyName;
        $this->dbms_ = $dbms;
    }
    
    public function getPropertyName() {
        return $this->propertyName_;
    }
    
    public function getDbms() {
        return $this->dbms_;
    }
    
    protected function executeSql(S2Container_DataSource $ds, $sql, $args) {
        $handler = new S2Dao_BasicSelectHandler($ds, $sql, self::$resultSetHandler_);
        return $handler->execute($args, null);
    }
    
    public function setIdentifier($bean, $value) {
        if($value instanceof S2Container_DataSource){
            $retVal = $this->executeSql($value,
                            $this->getDbms()->getIdentitySelectString(),
                            null);
            $this->setIdentifier($bean, $retVal);
        } else {
            if ($this->propertyName_ == null) {
                throw new S2Container_EmptyRuntimeException('propertyName');
            }
            $beanDesc = S2Container_BeanDescFactory::getBeanDesc(get_class($bean));
            $pd = $beanDesc->getPropertyDesc($this->propertyName_);
            $pd->setValue($bean, $value);
        }
    }
}
?>
