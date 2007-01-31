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
class S2Dao_IdentifierGeneratorFactory {

    private static $generatorClasses = array(
        'assigned' => 'S2Dao_AssignedIdentifierGenerator',
        'identity' => 'S2Dao_IdentityIdentifierGenerator',
        'sequence' => 'S2Dao_SequenceIdentifierGenerator'
    );
    private static $instance = array();
    
    private function __construct() {
    }

    public static function addIdentifierGeneratorClass($name, $clazz) {
        self::$generatorClasses[$name] = $clazz;
    }

    protected static function getGeneratorClass($name) {
        if (isset(self::$generatorClasses[$name])) {
            return self::$generatorClasses[$name];
        }
        return new $name;
    }
    
    public static function createIdentifierGenerator($propertyName,
                                                     S2Dao_Dbms $dbms,
                                                     $annotation = null) {
        if($annotation === null){
            return new S2Dao_AssignedIdentifierGenerator($propertyName, $dbms);
        }

        $array = S2Dao_ArrayUtil::spacetrim(preg_split('/(=|,)/', $annotation));
        $claz = self::getGeneratorClass($array[0]);
        $generator = self::createIdentifierGenerator2($claz, $propertyName, $dbms);
        for ($i = 1; $i < count($array); $i += 2) {
            self::setProperty($generator, trim($array[$i]), trim($array[$i + 1]));
        }
        return $generator;
    }
    
    public static function createIdentifierGenerator2($clazz, $propertyName, S2Dao_Dbms $dbms) {
        $refClazz = null;
        if(isset(self::$instance[$clazz])){
            $refClass = self::$instance[$clazz];
        } else {
            $refClass = self::$instance[$clazz] = new ReflectionClass($clazz);
        }
        return S2Container_ConstructorUtil::newInstance($refClass, array($propertyName, $dbms));
    }

    protected static function setProperty($generator, $propertyName, $value) {
        $class = new ReflectionClass(get_class($generator));
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc($class);
        $pd = $beanDesc->getPropertyDesc($propertyName);
        $pd->setValue($generator, $value);
    }
}
?>
