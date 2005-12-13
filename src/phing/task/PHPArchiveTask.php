<?php

class PHPArchiveTask extends Task {

    private $pharfile = null;
    private $inifile = null;
    private $usegzip = false;
    private $incFileSets = array();
    private $ignFileSets = array();

    public function init(){
        require_once "PHP/Archive.php";
        require_once "PHP/Archive/Creator.php";
    }

    public function main(){
        $phar = new PHP_Archive_Creator($this->inifile, $this->usegzip);

        $includefiles = array();
        foreach($this->incFileSets as $fileset){
            $includefiles[] = $this->getFileList($fileset);
        }

        $ignorefiles = array();
        foreach($this->ignFileSets as $fileset){
            foreach($this->getFileList($fileset) as $files){
                $ignorefiles[] = $files;
            }
        }

        $ignore = array();
        foreach($includefiles as $files){
            $c = count($files);
            for($i = 0; $i < $c; $i++){
                $file = $files[$i];
                if( !in_array($file, $ignorefiles) ){
                    echo "include: " . $file["fullpath"] . PHP_EOL;
                    $phar->addFile($file["fullpath"], $file["key"], false);
                } else {
                    $ignore[] = $file["fullpath"];
                }
            }
        }

        echo PHP_EOL;

        foreach($ignore as $file){
            echo "exclude: " . $file . PHP_EOL;
        }

        echo PHP_EOL;

        echo "making: " . $this->pharfile->getPath() . "...... ";
        if( $phar->savePhar($this->pharfile->getPath()) ){
            echo "Succeed." . PHP_EOL;
        } else {
            echo "Failure. orz" . PHP_EOL;
        }
    }

    public function setPharfile(PhingFile $pharfile){
        $this->pharfile = $pharfile;
    }

    public function setInifile($inifile = "index.php"){
        $this->inifile = $inifile;
    }

    public function setUsegzip($usegzip = false){
        $usegzip == 1 && $this->usegzip = true;
    }

    public function createPharFileSet(){
        $fs = new PharFileSet();
        $this->incFileSets[] = $fs;
        return $fs;
    }

    public function createIgnoreFileSet(){
        $fs = new IgnoreFileSet();
        $this->ignFileSets[] = $fs;
        return $fs;
    }

    private function getFileList(FileSet $fileset){
        $ds = $fileset->getDirectoryScanner($this->project);
        $files = $ds->getIncludedFiles();
        foreach($files as &$file){
            $fs = realpath($ds->getBaseDir() . DIRECTORY_SEPARATOR . $file);
            $file = array(
                        "fullpath" => $fs,
                        "key" => $file,
                    );
        }
        return $files;
    }
}

?>
