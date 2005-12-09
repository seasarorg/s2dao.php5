<?php

require_once "phing/types/AbstractFileSet.php";

class IgnoreFileSet extends FileSet {

    public function __construct($fileset = null) {
        parent::__construct($fileset);
    }
}

?>
