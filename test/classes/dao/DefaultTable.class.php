<?php

class DefaultTable {

    const TABLE = "DEFAULT_TABLE";
    const id_ID = "identity";

    private $id;
    private $aaa;
    private $bbb;
    private $versionNo;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getAaa() {
        return $this->aaa;
    }

    public function setAaa($defaultColumn) {
        $this->aaa = $defaultColumn;
    }

    public function getBbb() {
        return $this->bbb;
    }

    public function setBbb($bbb) {
        $this->bbb = $bbb;
    }

    public function getVersionNo() {
        return $this->versionNo;
    }

    public function setVersionNo($versionNo) {
        $this->versionNo = $versionNo;
    }
}

?>