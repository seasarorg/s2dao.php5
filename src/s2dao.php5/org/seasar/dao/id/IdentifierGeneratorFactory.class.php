<?php

/**
 * @author nowel
 */
class IdentifierGeneratorFactory {

    private static $generatorClasses_;
    public static $init = false;
    
    private function IdentifierGeneratorFactory() {
    }

    public static function staticConst(){
        self::$generatorClasses_ = new HashMap();
        self::addIdentifierGeneratorClass("assigned", "AssignedIdentifierGenerator");
        self::addIdentifierGeneratorClass("identity", "IdentityIdentifierGenerator");
        self::addIdentifierGeneratorClass("sequence", "SequenceIdentifierGenerator");
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
        return ClassUtil::forName($name);
    }
    
    public static function createIdentifierGenerator($propertyName, Dbms $dbms, $annotation = null) {
        if( $annotation == null ){
            return new AssignedIdentifierGenerator($propertyName, $dbms);
        }
        
        $array = explode("=, ", $annotation);
        $clazz = self::getGeneratorClass($array[0]);
        
        $construct = ClassUtil::getConstructot( $clazz, array("", get_class($dbms)) );
        $generator = ConstructorUtil::newInstance($construct, array($propertyName, $dbms));
        
        for ($i = 1; $i < count($array); $i += 2) {
            self::setProperty($generator, trim($array[$i]), trim($array[$i + 1]));
        }
        return $generator;
    }
    
    protected static function setProperty($generator, $propertyName, $value) {
        $beanDesc = BeanDescFactory::getBeanDesc(get_class($generator));
        $pd = $beanDesc->getPropertyDesc($propertyName);
        $pd->setValue($generator, $value);
    }
}

if( !IdentifierGeneratorFactory::$init ){
    IdentifierGeneratorFactory::staticConst();
}
?>
