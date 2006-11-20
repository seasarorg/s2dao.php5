<?php
require_once 'phing/Task.php';

/**
 * @author nowel
 */
class S2DaoTestRunnerTask extends Task {

    private $listener = array();
    private $complete = true;
    
    private $initializer;
    
    private $resourceDir;
    private $appDicon;
    private $daoDicon;
    private $pdoDicon;
    
    public function init(){
    }
    
    public function main(){
        $this->setup();
        $suites = array();
        foreach($this->listener as $listener){
            $listener->setInitializer($this->initializer);
            $suites[] = $listener->getTestSuites();
        }
        
        $results = array();
        foreach($suites as $suite){
            $result = new PHPUnit2_Framework_TestResult();
            $result->addListener($listener);
            $suite->run($result);
            $results[] = $result;
        }
        
        foreach($results as $res){
            if($res->wasSuccessful() && $res->allCompletlyImplemented()){
                continue;
            }
            $this->testsNotComplete($res);
        }
        if($this->complete){
            $this->log("All Completlyment!!");
        }
    }
    
    private function setup(){
        define('S2CONTAINER_PHP5_APP_DICON', $this->appDicon);
        define('S2CONTAINER_PHP5_LOG_LEVEL', S2Container_SimpleLogger::FATAL);
        define('S2CONTAINER_PHP5_DOM_VALIDATE', false);
        define('DAO_DICON', $this->daoDicon);
        define('PDO_DICON', $this->pdoDicon);
        
        $container = S2ContainerFactory::create(S2CONTAINER_PHP5_APP_DICON);
        $datasource = $container->getComponent("pdo.dataSource");
        $initializer = new UnitTest_DB_Initializer();
        $initializer->setResourceDir($this->resourceDir);
        $initializer->setDataSource($datasource);
        $initializer->initialize();
        $this->initializer = $initializer;
    }

    public function testsNotComplete(PHPUnit2_Framework_TestResult $result){
        $this->complete = false;
        foreach($result->failures() as $failure){
            $this->log("[failure] " . $failure->exceptionMessage());
        }
    }

    public function createListener(){
        $listener = new S2DaoTestListenerTask($this->project);
        $this->listener[] = $listener;
        return $listener;
    }
    
    public function setResourceDir($resourceDir){
        $this->resourceDir = $resourceDir;
    }
    
    public function setAppDicon($appDicon){
        $this->appDicon = $appDicon;
    }
    
    public function setDaoDicon($daoDicon){
        $this->daoDicon = $daoDicon;
    }
    
    public function setPdoDicon($pdoDicon){
        $this->pdoDicon = $pdoDicon;
    }

}

class UnitTest_DB_Initializer {
    
    private $dataSource;
    private $resourceDir;
    
    public function setResourceDir($resourceDir){
        $this->resourceDir = $resourceDir;
    }
    
    public function setDataSource(S2Container_DataSource $dataSource){
        $this->dataSource = $dataSource;
    }
    
    public function initialize(){
        try {
            $pdo = $this->dataSource->getConnection();
            $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
            
            $pdo->beginTransaction();
            $dbms = strtolower($driver);
            
            $sqlPath = $this->resourceDir . DIRECTORY_SEPARATOR . "test-" . $dbms . ".sql";
            $this->query($pdo, $sqlPath);
            $pdo->commit();
        } catch(PDOException $e){
            throw $e;
        }
    }
    
    private function query(PDO $pdo, $sqlfile){
        if(!file_exists($sqlfile)){
            throw new Exception("File not Exists: " . $sqlfile);
        }
        
        $pdo->query(file_get_contents($sqlfile));
    }
    
}

?>