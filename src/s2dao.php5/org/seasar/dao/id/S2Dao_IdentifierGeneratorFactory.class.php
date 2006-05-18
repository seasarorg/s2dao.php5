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
class S2Dao_IdentifierGeneratorFactory {

    private static $generatorClasses_;
    public static $init = false;
    
    private function S2Dao_IdentifierGeneratorFactory() {
    }

    public static function staticConst(){
        self::$generatorClasses_ = new S2Dao_HashMap();
        self::addIdentifierGeneratorClass('assigned', 'S2Dao_AssignedIdentifierGenerator');
        self::addIdentifierGeneratorClass('identity', 'S2Dao_IdentityIdentifierGenerator');
        self::addIdentifierGeneratorClass('sequence', 'S2Dao_SequenceIdentifierGenerator');
        self::$init = true;
    }
    
    public static function addIdentifierGeneratorClass($name, $clazz) {
        self::$generatorClasses_->put($name, $clazz);
    }

    protected static function getGeneratorClass($name) {
        $clazz = self::$generatorClasses_->get($name);
        if ($clazz != null) {
            return $clazz;
        }
        return new $name;
    }
    
    public static function createIdentifierGenerator($propertyName, S2Dao_Dbms $dbms, $annotation = null) {
        if($annotation == null){
            return new S2Dao_AssignedIdentifierGenerator($propertyName, $dbms);
        }
        
        $array = explode('=, ', $annotation);
        $clazz = self::getGeneratorClass($array[0]);
        
        $refCls = new ReflectionClass($clazz);
        $generator = S2Container_ConstructorUtil::newInstance($refCls,
                                          array($propertyName, $dbms));

        for ($i = 1; $i < count($array); $i += 2) {
            self::setProperty($generator, trim($array[$i]), trim($array[$i + 1]));
        }
        return $generator;
    }
    
    protected static function setProperty($generator, $propertyName, $value) {
        $beanDesc = S2Container_BeanDescFactory::getBeanDesc(get_class($generator));
        $pd = $beanDesc->getPropertyDesc($propertyName);
        $pd->setValue($generator, $value);
    }
}

if(!S2Dao_IdentifierGeneratorFactory::$init){
    S2Dao_IdentifierGeneratorFactory::staticConst();
}
?>
