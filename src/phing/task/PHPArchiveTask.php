<?php

class PHPArchiveTask extends Task {

    private $pharfile = null;
    private $inifile = null;
    private $usegzip = false;
    private $filesets = array();

    public function init(){
        require_once "PHP/Archive.php";
        require_once "PHP/Archive/Creator.php";

        if( !(class_exists("PHP_Archive") && class_exists("PHP_Archive_Creator")) ){
            throw new Exception("PHP_Archive... orz");
        }
    }

    public function main(){
        $phar = new PHP_Archive_Creator($this->inifile, $this->usegzip);
        $prefix = $this->fileset->getPrefix();
        foreach($this->getFileList() as $file){
            $phar->addFile($file["fullpath"], $file["key"], true);
        }
        $phar->savePhar($this->pharfile->getPath());
    }

    public function setPharfile(PhingFile $pharfile){
        $this->pharfile = $pharfile;
    }

    public function setInifile(PhingFile $inifile){
        $this->inifile = $inifile;
    }

    public function setUsegzip($usegzip = false){
        $usegzip == 1 && $this->usegzip = true;
    }

    public function createPharFileSet(){
        $this->fileset = new PharFileSet();
        return $this->fileset;
    }

    private function getFileList(){
        $ds = $this->fileset->getDirectoryScanner($this->project);
        $ds->scan();
        $files = $ds->getNotIncludedFiles();
        foreach($files as &$file){
            $fs = new PhingFile(basename($ds->getBaseDir()), $file);
            $file = array(
                        "fullpath" => $fs->getAbsolutePath(),
                        "key" => $file,
                    );
        }
        return $files;
    }
}

?>
