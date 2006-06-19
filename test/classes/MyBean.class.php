<?php

class MyBean {
    const TABLE = "MyBean";
    const bbb_COLUMN = "myBbb";
    const ccc_RELNO = 0;
    const ccc_RELKEYS = "ddd:id";

    private $aaa;
    private $bbb;
    private $ccc;
    private $ddd;

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

    public function getCcc() {
        return $this->ccc;
    }

    public function setCcc(MyBean $ccc) {
        $this->ccc = $ccc;
    }

    public function getDdd() {
        return $this->ddd;
    }

    public function setDdd($ddd) {
        $this->ddd = $ddd;
    }
}
?>