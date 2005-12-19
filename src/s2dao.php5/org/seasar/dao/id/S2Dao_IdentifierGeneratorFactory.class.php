<?php

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
        self::addIdentifierGeneratorClass("assigned", "S2Dao_AssignedIdentifierGenerator");
        self::addIdentifierGeneratorClass("identity", "S2Dao_IdentityIdentifierGenerator");
        self::addIdentifierGeneratorClass("sequence", "S2Dao_SequenceIdentifierGenerator");
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
        if( $annotation == null ){
            return new S2Dao_AssignedIdentifierGenerator($propertyName, $dbms);
        }
        
        $array = explode("=, ", $annotation);
        $clazz = self::getGeneratorClass($array[0]);
        
        $refCls = new ReflectionClass($clazz);
        $generator = S2Container_ConstructorUtil::newInstance($refCls, array($propertyName, $dbms));

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

if( !S2Dao_IdentifierGeneratorFactory::$init ){
    S2Dao_IdentifierGeneratorFactory::staticConst();
}
?>
