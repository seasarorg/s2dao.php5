<?php

/**
 * @author nowel
 */
class S2DaoSkeletonTask extends Task {
    
    const SkelDir = "/skel";
    
    protected $toDir;
    protected $skeldir;
    protected $dsn;
    protected $user;
    protected $pass;
    
    public function init(){
    }
    
    public function main(){
        $this->setupTask();
        $dbms = new S2DaoSkeletonDbms($this->dsn, $this->user, $this->pass);
        $skel = new S2DaoSkeletonGen($this->skeldir);
        $colset = $dbms->getAllColumns();
        foreach($colset as $table => $columns){
            $skel->setTableName($table);
            $skel->setColumns($columns);
            
            $skel->init();
            $this->generateDao($skel);
            $this->generateBean($skel);
            $this->generateDaoImpl($skel);
        }
        $this->log("[info] see the files");
        $files = glob($this->toDir . DIRECTORY_SEPARATOR . '*' . S2DaoSkeletonGen::ext);
        foreach($files as $file){
            $this->log("[file]: " . $file);
        }
    }
    
    public function setToDir($toDir){
        $this->toDir = $toDir;
    }
    
    protected function setupTask(){
        include_once "S2Container/S2Container.php";
        include_once "S2Dao/S2Dao.php";
        S2ContainerClassLoader::import(S2CONTAINER_PHP5);
        S2ContainerClassLoader::import(S2DAO_PHP5);
        function __autoload($class = null){
            S2ContainerClassLoader::load($class);
        }
        $project = $this->getProject();
        $srcdir = $project->getProperty("project.src.dir");
        $pjname = $project->getProperty("project.name");
        $this->dsn = $project->getProperty("dsn");
        $this->user = $project->getProperty("user");
        $this->pass = $project->getProperty("password");
        $this->skeldir = dirname(__FILE__) . self::SkelDir;
    }
    
    protected function generateDao(S2DaoSkeletonGen $skel){
        $this->log("[create] [Dao]: " . $skel->getDaoName());
        $path = $this->toDir . DIRECTORY_SEPARATOR . $skel->getDaoFileName();
        $this->write($path, $skel->generateDaoContent());
    }
    
    protected function generateBean(S2DaoSkeletonGen $skel){
        $this->log("[create] [Bean]: " . $skel->getBeanName());
        $path = $this->toDir . DIRECTORY_SEPARATOR . $skel->getEntityFileName();
        $this->write($path, $skel->generateBeanContent());
    }
    
    protected function generateDaoImpl(S2DaoSkeletonGen $skel){
        $this->log("[create] [DaoImpl]: " . $skel->getDaoImplName());
        $path = $this->toDir . DIRECTORY_SEPARATOR . $skel->getDaoImplFileName();
        $this->write($path, $skel->generateDaoImplContent());
    }
    
    protected function write($path, $content){
        @file_put_contents($path, $content);
    }
}

class S2DaoSkeletonDbms {
    
    protected $pdo = null;
    protected $tables = array();
    protected $columns = array();
    
    public function __construct($dsn, $user, $pass){
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->setupTables();
        $this->setupColumns();
    }
    
    public function __destruct(){
        unset($this->pdo);
    }
    
    protected function setupTables(){
        $dbms = S2Dao_DbmsManager::getDbms($this->pdo);
        $stmt = $this->pdo->query($dbms->getTableSql());
        $this->tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    protected function setupColumns(){
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
    
    protected $skelDir;
    protected $table;
    protected $columns = array();
    
    protected $dao;
    protected $entity;
    protected $daoImpl;
    
    protected $user;
    protected $date;
    
    const DateFormat = 'Y/m/d';
    
    const ext = ".class.php";
    
    const DaoName = "Dao";
    const DaoImplName = "DaoImpl";
    const BeanName = "Entity";
    
    public function __construct($skelDir){
        $this->dao = new DaoCreator($skelDir);
        $this->daoImpl= new DaoImplCreator($skelDir);
        $this->bean = new BeanCreator($skelDir);
        $this->user = getenv('USER');
        $this->date = date(self::DateFormat, time());
    }
    
    public function setTableName($table){
        $this->table = $table;
    }
    
    public function setColumns(array $columns){
        $this->columns = $columns;
    }
    
    public function init(){
        $clazz = ucfirst(strtolower($this->table));
        $this->dao->setName($clazz . self::DaoName);
        $this->daoImpl->setName($clazz . self::DaoImplName);
        $this->bean->setName($clazz. self::BeanName);
    }
    
    public function getDaoFileName(){
        return $this->getDaoName() . self::ext;
    }
    
    public function getEntityFileName(){
        return  $this->getBeanName() . self::ext;
    }
    
    public function getDaoImplFileName(){
        return $this->getDaoImplName() . self::ext;
    }
    
    public function getDaoName(){
        return $this->dao->getName();
    }
    
    public function getDaoImplName(){
        return $this->daoImpl->getName();
    }
    
    public function getBeanName(){
        return $this->bean->getName();
    }
    
    public function replaceCommon($copy){
        $place = array(
            '{date}' => $this->date,
            '{user}' => $this->user,
            '{daoName}' => $this->getDaoName(),
            '{daoImplName}' => $this->getDaoImplName(),
            '{beanName}' => $this->getBeanName(),
            '{tableName}' => $this->table,
        );
        return str_replace(array_keys($place), array_values($place), $copy);
    }
    
    public function generateDaoContent(){
        return $this->replaceCommon($this->dao->generate($this->getBeanName()));
    }

    public function generateDaoImplContent(){
        return $this->replaceCommon($this->daoImpl->generate($this->table,
                                                             $this->columns[0],
                                                             $this->getDaoName(),
                                                             $this->getBeanName()));
    }
    
    public function generateBeanContent(){
        return $this->replaceCommon($this->bean->generate($this->table, $this->columns));
    }
    
}

abstract class AbstractCreator {
    
    const SEPARATOR_CHAR = '_';
    
    private $name;
    private $skelDir;
    
    public function __construct($skelDir){
        $this->skelDir = $skelDir;
    }
    
    public function getSkeleton($file){
        $path = $this->skelDir . DIRECTORY_SEPARATOR . $file;
        if(!is_readable($path)){
            throw new Exception('file not readable: ' . $path); 
        }
        return file_get_contents($path);
    }
    
    public function replaceSeparator($value){
        $name = '';
        $token = strtok($value, self::SEPARATOR_CHAR);
        while ($token){
            $name .= ucfirst(strtolower($token));
            $token = strtok(self::SEPARATOR_CHAR);
        }
        return $name;
    }
    
    public function join(array $array){
        return implode(PHP_EOL, $array);
    }
    
    public function replace($folder, $value, $content){
        return str_replace($folder, $value, $content);
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setName($name){
        $this->name = $name;
    }
}

class BeanCreator extends AbstractCreator {
    
    const bean_header = "/BeanHeader.skel";
    const bean_footer = "/BeanFooter.skel";
    
    const property_header = "/BeanProperties_header.skel";
    const property_footer = "/BeanProperties_footer.skel";
    const property_body = "/BeanProperty_body.skel";
    
    const method_header = "/BeanMethods_header.skel";
    const method_footer = "/BeanMethods_footer.skel";
    const method_setter = "/BeanMethod_setter.skel";
    const method_getter = "/BeanMethod_getter.skel";
    const method_append = "/BeanMethod_append.skel";
    
    const const_header = "/BeanConstants_header.skel";
    const const_footer = "/BeanConstants_footer.skel";
    const const_body = "/BeanConstant_body.skel";
    
    const COLUMN_SUFFIX = "_COLUMN";
    
    public function __construct($skelDir){
        parent::__construct($skelDir);
        
        $this->beanHeader = $this->getSkeleton(self::bean_header);
        $this->beanFooter = $this->getSkeleton(self::bean_footer);
        
        $this->propertyHeader = $this->getSkeleton(self::property_header);
        $this->propertyFooter = $this->getSkeleton(self::property_footer);
        $this->propertyBody = $this->getSkeleton(self::property_body);
        
        $this->methodHeader = $this->getSkeleton(self::method_header);
        $this->methodFooter = $this->getSkeleton(self::method_footer);
        $this->methodSetter = $this->getSkeleton(self::method_setter);
        $this->methodGetter = $this->getSkeleton(self::method_getter);
        $this->methodAppend = $this->getSkeleton(self::method_append);
        
        $this->constHeader = $this->getSkeleton(self::const_header);
        $this->constFooter = $this->getSkeleton(self::const_footer);
        $this->constbody = $this->getSkeleton(self::const_body);
    }
    
    public function generate($table, $columns){
        $consts = array();
        $properties = array();
        $methods = array();
        foreach($columns as $column){
            $propertyName = $this->createProperty($column);
            $consts[] = $this->replaceConstants($propertyName, $column);
            $properties[] = $this->replaceProperty($propertyName);
            $methods[] = $this->replaceMethod($propertyName);
        }
        $methods[] = $this->methodAppend;
        $buf = array();
        $buf = array_merge($buf, array($this->beanHeader));
        $buf = array_merge($buf, array($this->constHeader));
        $buf = array_merge($buf, $consts);
        $buf = array_merge($buf, array($this->constFooter));
        $buf = array_merge($buf, array($this->propertyHeader));
        $buf = array_merge($buf, $properties);
        $buf = array_merge($buf, array($this->propertyFooter));
        $buf = array_merge($buf, array($this->methodHeader));
        $buf = array_merge($buf, $methods);
        $buf = array_merge($buf, array($this->methodFooter));
        $buf = array_merge($buf, array($this->beanFooter));
        return $this->replaceBean($table, $this->join($buf));
    }
    
    private function createProperty($name){
        $propertyName = $this->replaceSeparator($name);
        return strtolower($propertyName[0]) . substr($propertyName, 1);
    }
    
    private function replaceProperty($propertyName){
        return $this->replacePropertyAsContent($propertyName, $this->propertyBody);
    }
    
    private function replacePropertyAsContent($propertyName, $content){
        return $this->replace('{propertyName}', $propertyName, $content);
    }
    
    private function replaceMethod($propertyName){
        $methodName = ucfirst($propertyName);
        $buf = array();
        $buf[] = $this->methodGetter;
        $buf[] = $this->methodSetter;
        return $this->replacePropertyAsContent($propertyName,
                    $this->replace('{methodName}', $methodName, $this->join($buf)));
    }
    
    private function replaceConstants($propertyName, $columnName){
        $constName = $propertyName . self::COLUMN_SUFFIX;
        return $this->replace('{constName}', $constName,
                $this->replace('{constValue}', $columnName, $this->constbody));
    }
    
    private function replaceBean($tableName, $content){
        return $this->replace('{beanName}', $this->getName(), $content);
    }
    
}

class DaoCreator extends AbstractCreator {
    
    const dao_header = "/DaoHeader.skel";
    const dao_footer = "/DaoFooter.skel";
    const dao_constant = "/DaoConstants.skel";
    const dao_method = "/DaoMethods.skel";
    
    public function __construct($skelDir){
        parent::__construct($skelDir);
        $this->daoHeader = $this->getSkeleton(self::dao_header);
        $this->daoFooter = $this->getSkeleton(self::dao_footer);
        $this->daoConstant = $this->getSkeleton(self::dao_constant);
        $this->daoMethod = $this->getSkeleton(self::dao_method);
    }
    
    public function generate($beanName){
        $buf = array();
        $buf[] = $this->daoHeader;
        $buf[] = $this->daoConstant;
        $buf[] = $this->daoMethod;
        $buf[] = $this->daoFooter;
        return $this->replace('{beanName}', $beanName,
                $this->replace('{daoName}', $this->getName(), $this->join($buf)));
    }
}

class DaoImplCreator extends AbstractCreator {
    
    const daoImpl_header = "/DaoImplHeader.skel";
    const daoImpl_footer = "/DaoImplFooter.skel";
    const daoImpl_constant = "/DaoImplConstants.skel";
    const daoImpl_method = "/DaoImplMethods.skel";
    
    public function __construct($skelDir){
        parent::__construct($skelDir);
        $this->daoImplHeader = $this->getSkeleton(self::daoImpl_header);
        $this->daoImplFooter = $this->getSkeleton(self::daoImpl_footer);
        $this->daoImplConstant = $this->getSkeleton(self::daoImpl_constant);
        $this->daoImplMethod = $this->getSkeleton(self::daoImpl_method);
    }
    
    public function generate($table, $column, $daoName, $beanName){
        $buf = array();
        $buf[] = $this->daoImplHeader;
        $buf[] = $this->daoImplConstant;
        $buf[] = $this->daoImplMethod;
        $buf[] = $this->daoImplFooter;
        $template = $this->join($buf);
        $template = $this->replace('{query}', 'ORDER BY ' . $column . ' ASC', $template);
        $template = $this->replace('{daoName}', $daoName, $template);
        $template = $this->replace('{beanName}', $beanName, $template);
        return $this->replace('{daoImplName}', $this->getName(), $template);
    }
    
}

?>