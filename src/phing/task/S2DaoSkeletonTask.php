<?php

/**
 * @author nowel
 */
class S2DaoSkeletonTask extends Task {
    
    const clzz = ".class.php";
    const ClassLoader = "S2DaoClassLoader";
    
    const DaoName = "Dao";
    const DtoName = "Dto";
    const BeanName = "Entity";
    
    const REP_DAO = "@@DAO@@";
    const REP_BEAN = "@@BEAN@@";
    const REP_TABLE = "@@TABLE@@";
    const REP_COLUMN = "@@COLUMN@@";
    const REP_PROP = "@@PROP@@";
    const REP_PROPS = "@@PROPS@@";
    const REP_METHODS = "@@METHODS@@";
    
    const SEP_CHAR = '_';
    
    const PROPERTY = 'private $@@PROP@@;';
    const ANNO_COLUMN = 'const @@PROP@@_COLUMN = "@@COLUMN@@";';

    const SkelDir = "/skel";
    const DaoFile = "/Dao.php.skel";
    const DtoFile = "/Dto.php.skel";
    const EntityFile = "/Bean.php.skel";
    
    private $toDir = "";
    private $skeldir = "";
    private $dsn = "";
    private $user = "";
    private $pass = "";
    
    public function init(){
    }
    
    public function main(){
        $this->setupTask();
        $dbms = new S2DaoSkeletonDbms($this->dsn, $this->user, $this->pass);
        $skel = new S2DaoSkeletonGen($this->skeldir);
        foreach($dbms->getAllColumns() as $table => $columns){
            $skel->setTableName($table);
            $skel->setColumns($columns);
            $this->log("[create] [DAO]: " . $table . self::DaoName);
            $path = $this->toDir . DIRECTORY_SEPARATOR . $table . self::DaoName . self::clzz;
            file_put_contents($path, $skel->createDao());
            $this->log("[create] [BEAN]: " . $table . self::BeanName);
            $path = $this->toDir . DIRECTORY_SEPARATOR . $table . self::BeanName . self::clzz;
            file_put_contents($path, $skel->createEntity());
        }
        $this->log("[info] see the files");
        $files = glob($this->toDir . DIRECTORY_SEPARATOR . '*' . self::clzz);
        foreach($files as $file){
            $this->log("[file]: " . $file);
        }
    }
    
    public function setToDir($toDir){
        $this->toDir = $toDir;
    }
    
    protected function setupTask(){
        $srcdir = $this->getProject()->getProperty("project.src.dir");
        $pjname = $this->getProject()->getProperty("project.name");
        $this->dsn = $this->getProject()->getProperty("dsn");
        $this->user = $this->getProject()->getProperty("user");
        $this->pass = $this->getProject()->getProperty("password");
        $this->skeldir = dirname(__FILE__) . self::SkelDir;
        
        define("S2DAO_PHP5", $srcdir . DIRECTORY_SEPARATOR . $pjname);
        
        include S2DAO_PHP5 . DIRECTORY_SEPARATOR . self::ClassLoader. self::clzz;
        if(!class_exists(self::ClassLoader)){
            throw new BuildException(__CLASS__ . "required:" . self::ClassLoader);
        }
    }
}

class S2DaoSkeletonDbms {
    
    private $pdo = null;
    private $tables = array();
    private $columns = array();
    
    public function __construct($dsn, $user, $pass){
        S2DaoClassLoader::load("S2Dao_DatabaseMetaDataUtil");
        S2DaoClassLoader::load("S2Dao_DbmsManager");
        S2DaoClassLoader::load("S2Dao_HashMap");
        S2DaoClassLoader::load("S2Dao_ArrayList");
        S2DaoClassLoader::load("S2Dao_Dbms");
        
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->setupTables();
        $this->setupColumns();
    }
    
    public function __destruct(){
        unset($this->pdo);
    }
    
    private function setupTables(){
        $dbms = S2Dao_DbmsManager::getDbms($this->pdo);
        $stmt = $this->pdo->query($dbms->getTableSql());
        $this->tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    private function setupColumns(){
        foreach($this->tables as $table){
            $cols = S2Dao_DatabaseMetaDataUtil::getColumns($this->pdo, $table);
            $this->columns[$table] = $cols;
        }
    }
    
    public function getTables(){
        return $this->tables;
    }
    
    public function getColumns($table){
        return $this->columns[$table];
    }
    
    public function getAllColumns(){
        return $this->columns;
    }
}

class S2DaoSkeletonGen {
    
    private $skelDir = "";
    private $table;
    private $columns = array();
    
    private $dao = "";
    private $dto = "";
    private $entity = "";
    
    const DaoFile = S2DaoSkeletonTask::DaoFile;
    const DtoFile = S2DaoSkeletonTask::DtoFile;
    const EntityFile = S2DaoSkeletonTask::EntityFile;
    
    const DaoName = S2DaoSkeletonTask::DaoName;
    const BeanName = S2DaoSkeletonTask::BeanName;

    const REP_DAO = S2DaoSkeletonTask::REP_DAO;
    const REP_BEAN = S2DaoSkeletonTask::REP_BEAN;
    const REP_TABLE = S2DaoSkeletonTask::REP_TABLE;
    const REP_COLUMN = S2DaoSkeletonTask::REP_COLUMN;
    const REP_PROP = S2DaoSkeletonTask::REP_PROP;
    const REP_PROPS = S2DaoSkeletonTask::REP_PROPS;
    const REP_METHODS = S2DaoSkeletonTask::REP_METHODS;
    
    const PROPERTY = S2DaoSkeletonTask::PROPERTY;
    const ANNO_COLUMN = S2DaoSkeletonTask::ANNO_COLUMN;
    const SEP_CHAR = S2DaoSkeletonTask::SEP_CHAR;
    
    public function __construct($skelDir){
        $this->skelDir = $skelDir;
        if(is_readable($skelDir . self::DaoFile)){
            $this->dao = file_get_contents($skelDir . self::DaoFile);
        }
        if(is_readable($skelDir . self::DtoFile)){
            $this->dto = file_get_contents($skelDir . self::DtoFile);
        }
        if(is_readable($skelDir . self::EntityFile)){
            $this->entity = file_get_contents($skelDir . self::EntityFile);
        }
    }
    
    public function setTableName($table){
        $this->table = $table;
    }
    
    public function setColumns(array $columns){
        $this->columns = $columns;
    }
    
    public function createDao(){
        $copy = $this->dao;
        $daoName = $this->table . self::DaoName;
        $beanName = $this->table . self::BeanName;
        $copy = str_replace(self::REP_DAO, $daoName, $copy);
        $copy = str_replace(self::REP_BEAN, $beanName, $copy);
        return $copy;
    }
    
    public function createDto(){
        return $this->createEntity();
    }
    
    public function createEntity(){
        $copy = $this->entity;
        $properties = array();
        $annotations = array();
        $methods = array();
        $annocol = self::ANNO_COLUMN;
        foreach($this->columns as $column){
            $prop = $this->getProperty($column);
            $anno = str_replace(self::REP_PROP, $prop, $annocol);
            
            $annotations[] = str_replace(self::REP_COLUMN, $column, $anno);
            $properties[] = str_replace(self::REP_PROP, $prop, self::PROPERTY);
            $methods[] = implode($this->createGetter($prop));
            $methods[] = implode($this->createSetter($prop));
        }
        
        $field = implode(PHP_EOL, $annotations) . PHP_EOL;
        $field .= implode(PHP_EOL, $properties);
        $copy = str_replace(self::REP_PROPS, $field, $copy);
        $copy = str_replace(self::REP_METHODS, implode(PHP_EOL, $methods), $copy);
        $copy = str_replace(self::REP_TABLE, $this->table, $copy);
        $copy = str_replace(self::REP_BEAN, $this->table . self::BeanName, $copy);
        return $copy;
    }
    
    private function getProperty($column){
        $nameArr = str_split($this->getMethodName($column));
        $nameArr[0] = strtolower($nameArr[0]);
        return implode($nameArr);
    }
    
    private function getMethodName($propName){
        $name = '';
        $token = strtok($propName, self::SEP_CHAR);
        while ($token)
        {
            $name .= ucfirst(strtolower($token));
            $token = strtok(self::SEP_CHAR);
        }
        return $name;
    }

    private function createGetter($propName){
        $methodName = $this->getMethodName($propName);
        $getter = array();
        $getter[] = 'public function ';
        $getter[] = 'get' . $methodName . '(){';
        $getter[] = 'return $this->' . $propName . ';';
        $getter[] = '}';
        return $getter;
    }
    
    private function createSetter($propName){
        $methodName = $this->getMethodName($propName);
        $setter = array();
        $setter[] = 'public function ';
        $setter[] = 'set' . $methodName . '($' . $propName . '){';
        $setter[] = '$this->' . $propName . ' = $' . $propName . ';';
        $setter[] = '}';
        return $setter;
    }
}

?>