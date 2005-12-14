<?php

class PHPArchiveTask extends Task {

    private $pharfile = null;
    private $inifile = null;
    private $usegzip = false;
    private $incFileSets = array();
    private $filterChains = array();

    public function init(){
        include_once "PHP/Archive/Creator.php";
        if(!class_exists("PHP_Archive_Creator")){
            throw new BuildException("PHP_Archive_Creator...orz");
        }
    }

    public function main(){
        $phar = new PHP_Archive_Creator($this->inifile, $this->usegzip);

        $includefiles = array();
        foreach($this->incFileSets as $fileset){
            $includefiles[] = $this->getFileList($fileset);
        }

        foreach($includefiles as $files){
            $c = count($files);
            for($i = 0; $i < $c; $i++){
                try {
                    $contents = "";
                    $in = FileUtils::getChainedReader(new FileReader($files[$i]["path"]),
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

                $this->log("[add] file: " . $files[$i]["path"]);
                $phar->addString($contents, $this->replacePath($files[$i]["key"]), false);
            }
        }

        $this->log("[make] file: " . $this->pharfile->getPath() . "......");
        if( $phar->savePhar($this->pharfile->getPath()) ){
            $this->log("Succeed.");
        } else {
            $this->log("Failure. orz");
        }
    }

    public function setPharfile(PhingFile $pharfile){
        $this->pharfile = $pharfile;
    }

    public function setInifile($inifile = "index.php"){
        $this->inifile = $inifile;
    }

    public function setUsegzip($usegzip = false){
        $usegzip == "1" && $this->usegzip = true;
    }

    public function createPharFileSet(){
        $fs = new PharFileSet();
        $this->incFileSets[] = $fs;
        return $fs;
    }

    public function createFilterChain(){
        $fc = new FilterChain($this->project);
        $this->filterChains[] = $fc;
        return $fc;
    }

    private function getFileList(FileSet $fileset){
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

    private function replacePath($pathstr){
        return str_replace(DIRECTORY_SEPARATOR, '/', $pathstr);
    }

}

?>
