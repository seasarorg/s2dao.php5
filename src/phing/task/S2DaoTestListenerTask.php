<?php
require_once 'phing/types/FileSet.php';
require_once 'PHPUnit2/Framework/TestListener.php';

/**
 * @author nowel
 */
class S2DaoTestListenerTask implements PHPUnit2_Framework_TestListener {

    const DIR_SEP = DIRECTORY_SEPARATOR;
    private $project = null;
    private $filesets = array();
    private $suiteTimer = null;
    private $timer = null;
    
    private $initializer;
    
    public function __construct(Project $project){
        $this->project = $project;
    }
    
    public function setInitializer($initializer){
        $this->initializer = $initializer;
    }
    
    public function addFileSet(FileSet $fileset){
        $this->filesets[] = $fileset;
    }

    public function getTestSuites(){
        $suites = array();
        foreach($this->filesets as $fileset){
            $ds = $fileset->getDirectoryScanner($this->project);
            $ds->scan();
            
            $files = $ds->getIncludedFiles();
            foreach($files as $file){
                //$path = $this->project->getBasedir()->getPath() . self::DIR_SEP;
                $path = $ds->getBaseDir() . self::DIR_SEP  . $file;
                if(!file_exists($path)){
                    continue;
                }
                $this->project->log('[require_once]' . basename($path));
                require_once $path;

                $filePath = basename($path);
                $class = StringHelper::root($filePath, '.class.php');
                if($filePath == $class){
                    $class = StringHelper::root($filePath, '.php');
                }
                $suites[] = new PHPUnit2_Framework_TestSuite(new ReflectionClass($class));
            }
        }
        return $suites;
    }
    
    public function addError(PHPUnit2_Framework_Test $test, Exception $e){
        echo $test->getName() . "エラーが発生しました: Message[" . $e->getMessage() , "]" . PHP_EOL;
    }

    public function addFailure(PHPUnit2_Framework_Test $test, PHPUnit2_Framework_AssertionFailedError $e){
        echo $test->getName() . "テストが失敗しました: Message[" . $e->getMessage() , "]" . PHP_EOL;
    }

    public function addIncompleteTest(PHPUnit2_Framework_Test $test, Exception $e){
        echo $test->getName() . "テストが完了しませんでした: Message[" . $e->getMessage() , "]" . PHP_EOL;
    }

    public function startTestSuite(PHPUnit2_Framework_TestSuite $suite){
        $this->initializer->initialize();
        echo PHP_EOL;
        echo "テストシート[" . $suite->getName() . "]を開始(" . $suite->countTestCases() . ")" . PHP_EOL;
        $this->suiteTimer = new Timer();
        $this->suiteTimer->start();
    }

    public function endTestSuite(PHPUnit2_Framework_TestSuite $suite){
        echo "テストシート[" . $suite->getName() . "]を終了" . PHP_EOL;
        $this->suiteTimer->stop();
        echo "このシートの経過時間: " . $this->suiteTimer->getElapsedTime();
        echo PHP_EOL;
        echo PHP_EOL;
    }

    public function startTest(PHPUnit2_Framework_Test $test){
        echo "テスト[" . $test->getName() . "]を実行(" . $test->countTestCases() .")" . PHP_EOL;
        $this->timer = new Timer();
        $this->timer->start();
    }

    public function endTest(PHPUnit2_Framework_Test $test){
        echo "テスト[" . $test->getName() . "]を終了" . PHP_EOL;
        $this->timer->stop();
        echo "このテストの経過時間: " . $this->timer->getElapsedTime();
        echo PHP_EOL;
    }

}

?>