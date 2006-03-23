<?php

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
    
    protected function setIdentifier($bean, $value) {
        if($value instanceof S2Container_DataSource){
            $val = $this->executeSql($value,
                            $this->getDbms()->getIdentitySelectString(),
                            null);
            $this->setIdentifier($bean, $val);
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
