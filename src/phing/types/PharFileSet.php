<?php

require_once "phing/types/AbstractFileSet.php";

class PharFileSet extends FileSet {

    private $prefix = null;

    public function __construct($fileset = null) {
        parent::__construct($fileset);
    }

    public function setPrefix($prefix){
        $this->prefix = $prefix;
    }

    public function getPrefix(){
        return $this->prefix;
    }

}

?>
