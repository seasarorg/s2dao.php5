<?php

// require
require_once "PHPUnit2/Util/Skeleton.php";
require_once "PHPUnit2/Framework/TestCase.php";

/**
 * @author nowel
 */
class S2DaoTestGenTask extends Task {
    
    const php_ext = ".class.php";
    const test_ext = ".php";
    const test_file_prefix = "Test.php";
    const all_test_class_prefix = "AllTests";
    const skelDir = "/skel";
    
    const ClassHeaderFile = "/test.ClassHeader.skel";
    const ClassFooterFile = "/test.ClassFooter.skel";
    const ClassMethodFile = "/test.ClassMethod.skel";
    
    const AllTestHeaderFile = "/test.all.Header.skel";
    const AllTestFooterFile = "/test.all.Footer.skel";
    const AllTestContentFile = "/test.all.Content.skel";
    
    private $filesets = array();
    
    public function init(){
        include_once "S2Container/S2Container.php";
        if(!class_exists("S2ContainerClassLoader")){
            throw new BuildException("S2Container...orz");
        }
        if(function_exists("__autoload")){
            throw new BuildException("function __autoload already exists...orz");
        }
        
        S2ContainerClassLoader::import(S2CONTAINER_PHP5);
        function __autoload($class = null){
            //S2ContainerClassLoader::load($class);
            include_once "{$class}.class.php";
        }
    }
    
    public function main(){
        $skeldir = dirname(__FILE__) . self::skelDir;
        $srcdir = $this->getProject()->getProperty("test.src.dir");
        
        $files = array();
        $files = $this->getFiles($this->filesets[0]);
        $this->iniset($files);
        
        $headerFile = $skeldir . self::ClassHeaderFile;
        $footerFile = $skeldir . self::ClassFooterFile;
        $methodFile = $skeldir . self::ClassMethodFile;

        $makedFilePath = array();
        $skel = new TestCodeGenSkeleteon();
        $skel->setTemplates($headerFile, $footerFile, $methodFile);
 
        foreach($files as $file){
            $skel->init($file);
            if(!$skel->isPossibleContinue()){
                continue;
            }
            $writePath = dirname($srcdir . DIRECTORY_SEPARATOR . $file["projPath"]);
            $dir = new PhingFile($writePath);
            if(!$dir->exists()){
                $dir->mkdirs();
            }
            $makedFilePath[] = $skel->write($writePath);
        }

        $allHeader = $skeldir . self::AllTestHeaderFile;
        $allFooter = $skeldir . self::AllTestFooterFile;
        $allContent = $skeldir . self::AllTestContentFile;
        
        $testAll = new TestAllClassGen($makedFilePath);
        $testAll->setTemplates($allHeader, $allFooter, $allContent);
        $allTestsPath = $testAll->write($srcdir);
        $makedFilePath = array_merge($makedFilePath, $allTestsPath);

        $this->log("[info] see the files");
        foreach($makedFilePath as $file){
            $this->log("[file]: " . $file);
        }
    }
    
    public function createFileSet(){
        $fs = new FileSet();
        $this->filesets[] = $fs;
        return $fs;
    }
    
    private function getFiles(FileSet $fileset){
        $ds = $fileset->getDirectoryScanner($this->project);
        $files = $ds->getIncludedFiles();
        foreach($files as &$file){
            $path = realpath($ds->getBaseDir() . DIRECTORY_SEPARATOR . $file);
            $fileName = basename($file);
            $className = basename($file, self::php_ext);
            $file = array(
                        "fullPath" => $path, 
                        "projPath" => $file,
                        "fileName" => $fileName,
                        "className" => $className,
                    );
        }
        return $files;
    }
    
    private function iniset(array $files){
        $path = $this->getPackage($files);
        foreach($path as $pkg){
            //S2ContainerClassLoader::import($pkg);
            ini_set('include_path', $pkg . PATH_SEPARATOR . ini_get('include_path'));
        }
    }
    
    private function getPackage(array $files){
        $path = array();
        foreach($files as $file){
            $dir = dirname($file["fullPath"]);
            $path[$dir] = $dir;
        }
        return $path;
    }
}

class TestCodeGenSkeleteon extends PHPUnit2_Util_Skeleton {

    public function __construct(){
    }

    public function init(array $file){
        $classFile = $file["fullPath"];
        $className = $file["className"];
        
        $clz = new ReflectionClass($className);
        if(!$clz->isAbstract() && !$clz->isInterface()){
            $this->classSourceFile = $classFile;
            $this->className = $className;
        } else {
            $this->classSourceFile = null;
            $this->className = null;
        }
    }
    
    public function __destruct(){
        $this->classSourceFile = null;
        $this->className = null;
    }

    public function setTemplateClassHeader($templateClassHeader){
        $this->templateClassHeader = $templateClassHeader;
    }
    
    public function setTemplateClassFooter($templateClassFooter){
        $this->templateClassFooter = $templateClassFooter;
    }
    
    public function setTemplateMethod($templateMethod){
        $this->templateMethod = $templateMethod;
    }
    
    public function isPossibleContinue(){
        return $this->classSourceFile != null && $this->className != null;
    }
    
    public function write($writeDir){
        $file = $this->className . S2DaoTestGenTask::test_file_prefix;
        $filePath = $writeDir . DIRECTORY_SEPARATOR . $file;
        file_put_contents($filePath, $this->generate());
        return $filePath;
    }
}

class TestAllClassGen {
    
    private $dirs = array();
    private $header = null;
    private $footer = null;
    private $content = null;
    
    private $alltests = array();
    
    public function __construct($files){
        $dir = array();
        foreach($files as $file){
            $dirname = dirname($file);
            $dir[$dirname] = $dirname;
        }
        $this->dirs = $dir;
    }
    
    public function setTemplates($header, $footer, $content){
        if(is_readable($header)){
            $this->header = file_get_contents($header);
        } else {
            $this->header = $header;
        }
        
        if(is_readable($footer)){
            $this->footer = file_get_contents($footer);
        } else {
            $this->footer = $footer;
        }
        
        if(is_readable($content)){
            $this->content = file_get_contents($content);
        } else {
            $this->content = $content;
        }
    }
    
    private function generate(){
        foreach($this->dirs as $dir){
            $className = ucfirst(basename($dir)) . S2DaoTestGenTask::all_test_class_prefix;
            $source = $this->generateHeader($className);
            $source .= $this->generateContent($dir);
            $source .= $this->generateFooter();
            $this->alltests[$className] = $source;
        }
    }
    
    public function write($dir){
        $this->generate();
        $file = new PhingFile($dir); 
        if(!$file->exists()){
            $file->mkdirs();
        }
        $genFiles = array();
        foreach($this->alltests as $className => $content){
            $filePath = $dir . DIRECTORY_SEPARATOR . $className . S2DaoTestGenTask::test_ext;
            file_put_contents($filePath, $content);
            $genFiles[] = $filePath;
        }
        return $genFiles;
    }
    
    private function generateHeader($className){
        $copy = str_replace("{className}", $className, $this->header);
        return $copy;
    }
    
    private function generateContent($dir){
        $files = glob($dir . DIRECTORY_SEPARATOR . '*' . S2DaoTestGenTask::test_ext);
        $contents = array();
        foreach($files as $file){
            $testClass = basename($file, S2DaoTestGenTask::test_ext);
            $contents[] = str_replace("{testClass}", $testClass, $this->content);
        }
        return implode(PHP_EOL, $contents);
    }
    
    private function generateFooter(){
        return $this->footer;
    }

}

?>