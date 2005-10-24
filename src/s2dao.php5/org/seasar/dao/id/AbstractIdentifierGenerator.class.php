<?php

/**
 * @author Yusuke Hata
 */
abstract class AbstractIdentifierGenerator implements IdentifierGenerator {

    private static $resultSetHandler_;
    private $propertyName_;
    private $dbms_;
    
    public function __construct($propertyName, Dbms $dbms) {
        self::$resultSetHandler_ = new ObjectResultSetHandler();
        $this->propertyName_ = $propertyName;
        $this->dbms_ = $dbms;
    }
    
    public function getPropertyName() {
        return $this->propertyName_;
    }
    
    public function getDbms() {
        return $this->dbms_;
    }
    
    protected function executeSql(DataSource $ds, $sql, $args) {
        $handler = new BasicSelectHandler($ds, $sql, $this->resultSetHandler_);
        return $handler->execute($args);
    }
    
    protected function setIdentifier($bean, $value) {
        if( $value instanceof DataSource ){
            $val = $this->executeSql($value,
                            $this->getDbms()->getIdentitySelectString(),
                            null);
            $this->setIdentifier($bean, $val);
        } else {
            if ($this->propertyName_ == null) {
                throw new EmptyRuntimeException("propertyName");
            }
            $beanDesc = BeanDescFactory::getBeanDesc(get_class($bean));
            $pd = $beanDesc->getPropertyDesc($this->propertyName_);
            $pd->setValue($bean, $value);
        }
    }
}
?>
