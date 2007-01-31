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
// $Id: $
//
/**
 * @author nowel
 * @package org.seasar.s2dao.id
 */
abstract class S2Dao_AbstractIdentifierGenerator implements S2Dao_IdentifierGenerator {

    private static $resultSetHandler;
    private $propertyName;
    private $dbms;
    
    public function __construct($propertyName, S2Dao_Dbms $dbms) {
        self::$resultSetHandler = new S2Dao_ObjectResultSetHandler();
        $this->propertyName = $propertyName;
        $this->dbms = $dbms;
    }
    
    public function getPropertyName() {
        return $this->propertyName;
    }
    
    public function getDbms() {
        return $this->dbms;
    }
    
    protected function executeSql(S2Container_DataSource $ds, $sql, $args) {
        $handler = new S2Dao_BasicSelectHandler($ds, $sql, self::$resultSetHandler);
        return $handler->execute($args, null);
    }
    
    public abstract function setIdentifier($bean, S2Container_DataSource $value);
    
    public function setIdentifier2($bean, $value) {
        if ($this->propertyName == null) {
            throw new S2Container_EmptyRuntimeException('propertyName');
        }
        $class = new ReflectionClass(get_class($bean));
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($class);
        $pd = $beanDesc->getPropertyDesc($this->propertyName);
        $pd->setValue($bean, $value);
    }
}
?>
