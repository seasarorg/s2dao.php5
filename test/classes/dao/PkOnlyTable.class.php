<?php

class PkOnlyTable {

    const TABLE = "PK_ONLY_TABLE";
    private $aaa;
    private $bbb;

    public function getAaa() {
        return $this->aaa;
    }

    public function setAaa($aaa) {
        $this->aaa = $aaa;
    }

    public function getBbb() {
        return $this->bbb;
    }

    public function setBbb($bbb) {
        $this->bbb = $bbb;
    }

}

?>