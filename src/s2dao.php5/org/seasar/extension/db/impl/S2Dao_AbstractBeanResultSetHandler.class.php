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
 */
abstract class S2Dao_AbstractBeanResultSetHandler implements S2Dao_ResultSetHandler {

    private $beanClass;
    private $beanDesc;

    public function __construct(ReflectionClass $beanClass) {
        $this->setBeanClass($beanClass);
    }

    public function getBeanClass() {
        return $this->beanClass;
    }

    public function setBeanClass(ReflectionClass $beanClass) {
        $this->beanClass = $beanClass;
        $this->beanDesc = S2Container_BeanDescFactory::getBeanDesc($beanClass);
    }

    protected function createPropertyTypes(array $rsmd) {
        $count = count($rsmd);
        $columnNames = array_keys($rsmd);
        $propertyTypes = array();
        for($i = 0; $i < $count; ++$i){
            $propertyName = str_replace('_', '', $columnNames[$i]);
            $propertyDesc = $this->getPropertyDesc($propertyName);
            $propertyTypes[] = new S2Dao_PropertyTypeImpl($propertyDesc, null, $columnNames[$i]);
        }
        return $propertyTypes;
    }

    protected function createRow(array $rs, array $propertyTypes) {
        $row = $this->beanDesc->getBeanClass()->newInstance();
        $c = count($propertyTypes);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $propertyTypes[$i];
            $pd = $pt->getPropertyDesc();
            if($pd === null){
                continue;
            }
            $pd->setValue($row, $rs[$pt->getColumnName()]);
        }
        return $row;
    }
    
    private function getPropertyDesc($columnName){
        if($this->beanDesc->hasPropertyDesc($columnName)){
            return $this->beanDesc->getPropertyDesc($columnName);
        }
        $lower = strtolower($columnName);
        if($this->beanDesc->hasPropertyDesc($lower)){
            return $this->beanDesc->getPropertyDesc($lower);
        }
        $upper = strtoupper($columnName);
        if($this->beanDesc->hasPropertyDesc($upper)){
            return $this->beanDesc->getPropertyDesc($upper);
        }
    }
}

?>