<?php

/**
 * @author nowel
 */
class PHPArchiveTask extends Task {

    protected $pharFile = "";
    protected $inifile = null;
    protected $usegzip = false;
    protected $incFileSets = array();
    protected $filterChains = array();

    public function main(){
        include_once "PHP/Archive/Creator.php";
        if(!class_exists("PHP_Archive_Creator", false)){
            throw new BuildException("PHP_Archive_Creator...orz");
        }
        $this->create();
    }

    public function create(){
        $phar = new PHP_Archive_Creator($this->inifile, $this->usegzip);

        $includefiles = array();
        foreach($this->incFileSets as $fileset){
            $includefiles[] = $this->getFileList($fileset);
        }

        foreach($includefiles as $files){
            $c = count($files);
            for($i = 0; $i < $c; $i++){
                $file = $files[$i];
                try {
                    $contents = "";
                    $in = FileUtils::getChainedReader(new FileReader($file["path"]),
                                                      $this->filterChains,
                                                      $this->project);
                    while(-1 !== ($buffer = $in->read())) {
                        $contents .= $buffer;
                    }
                    $in->close();
                } catch (Exception $e){
                    if($in) $in->close();
                    $this->log($e->getMessage());
                }

                $this->log("[add] file: " . $file["path"]);
                $phar->addString($contents, $this->replacePath($file["file"]), false);
            }
        }

        $this->log("[create] file: " . $this->pharFile . "......");
        if($phar->savePhar($this->pharFile)){
            $this->log("Succeed.");
        } else {
            $this->log("Failure. orz");
        }
    }

    public function setPharFile($filepath){
        $this->pharFile = $filepath;
    }

    public function setInifile($inifile = "index.php"){
        $this->inifile = $inifile;
    }

    public function setUsegzip($usegzip = false){
        $usegzip == "1" && $this->usegzip = true;
    }

    public function createFileSet(){
        $fs = new FileSet();
        $this->incFileSets[] = $fs;
        return $fs;
    }

    public function createFilterChain(){
        $fc = new FilterChain($this->project);
        $this->filterChains[] = $fc;
        return $fc;
    }

    protected function getFileList(FileSet $fileset){
        $ds = $fileset->getDirectoryScanner($this->project);
        $files = $ds->getIncludedFiles();
        foreach($files as &$file){
            $path = realpath($ds->getBaseDir() . DIRECTORY_SEPARATOR . $file);
            $file = array(
                        "path" => $path, 
                        "key" => $file
                    );
        }
        return $files;
    }

    protected function replacePath($pathstr){
        return str_replace(DIRECTORY_SEPARATOR, '/', $pathstr);
    }

}

?>
