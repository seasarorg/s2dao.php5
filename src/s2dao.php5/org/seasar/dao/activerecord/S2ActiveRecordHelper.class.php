<?php

/**
 * @author nowel
 */
class S2ActiveRecordHelper {
    
    private $connection;
    private $beanDesc;
    private $daoReader;
    private $beanReader;
    private $dbms;
    private $table;
    private $columns = array();
    private $primaryKeys = array();
    private $recursMethodName = array();
    
    public function __construct(S2Container_DataSource $dataSource,
                                ReflectionClass $clazz){
        $annotationReaderFactory = new S2Dao_FieldAnnotationReaderFactory();
        $this->beanDesc = S2Container_BeanDescFactory::getBeanDesc($clazz);
        $this->daoReader = $annotationReaderFactory->createDaoAnnotationReader($this->beanDesc);
        $this->beanReader = $annotationReaderFactory->createBeanAnnotationReader($clazz);
        $this->connection = $dataSource->getConnection();
        $this->dbms = S2Dao_DbmsManager::getDbms($this->connection);
        
        $this->setupSqlCommand();
        $this->setupTable();
        $this->setupColumns();
    }
    
    protected function setupSqlCommand(){
        $methods = $this->beanDesc->getBeanClass()->getMethods();
        foreach($methods as $method){
            if($this->isUserDefinedMethod($method)){
                $this->setupMethod($method);
            }
        }
    }
    
    protected function setupMethod(ReflectionMethod $method){
        if($this->isConcealedMethod($method)){
            //$this->recursMethodName[] = null;
        }
    }
    
    protected function setupTable(){
        $ta = $this->beanReader->getTableAnnotation();
        if($ta != null){
            $this->table = $ta;
        } else {
            $this->table = $this->beanDesc->getBeanClass()->getName();
        }
    }
    
    protected function setupColumns(){
        $conn = $this->connection;
        $this->primaryKeys = S2Dao_DatabaseMetaDataUtil::getPrimaryKeys($conn, $this->table);
        $this->columns = S2Dao_DatabaseMetaDataUtil::getColumns($conn, $this->table);
    }
    
    public function getTable(){
        return $this->table;
    }
    
    public function getPrimeryKey($name){
    }
    
    public function getColumn($name){
    }
    
    public function getPrimaryKeyNames(){
        return $this->primaryKeys;
    }
    
    public function getColumnNames(){
        return $this->columns;
    }
    
    public function bindArgs(PDOStatement $ps, array $args){
        $values = array_values($args);
        $c = count($args);
        
        try {
            for($i = 0; $i < $c; $i++){
                $phpType = gettype($values[$i]);
                $ps->bindValue($i + 1, $values[$i], S2Dao_PDOType::gettype($phpType));
            }
        } catch (Exception $e) {
            throw new S2Container_SQLRuntimeException($e);
        }
    }
    
    public function query($sql){
        return $this->connection->query($sql);
    }
    
    public function execute($sql){
        return $this->connection->exec($sql);
    }
    
    public function prepare($sql){
        return $this->connection->prepare($sql);
    }
    
    public function getConnection(){
        return $this->connection;
    }
    
    public function getMethodSql($methodName){
        $class = $this->beanDesc->getBeanClass();

        $dir = dirname($class->getFileName());
        $base =  $dir . DIRECTORY_SEPARATOR . $class->getName() . '_' . $methodName;
        $dbmsPath = $base . $this->dbms->getSuffix() . '.sql';
        $standardPath = $base . '.sql';

        if (file_exists($dbmsPath)) {
            return file_get_contents($dbmsPath);
        } else if (file_exists($standardPath)) {
            return file_get_contents($standardPath);
        } else {
            return null;
        }
    }
    
    public function isRecursiveMethod($methodName){
        return in_array($methodName, $this->recursMethodName, true);
    }
    
    protected function isUserDefinedMethod(ReflectionMethod $method){
        return $method->getDeclaringClass() == $this->beanDesc->getBeanClass();
    }
    
    protected function isConcealedMethod(ReflectionMethod $method){
        return $method->isPrivate() || $method->isProtected();
    }
    
}

?>