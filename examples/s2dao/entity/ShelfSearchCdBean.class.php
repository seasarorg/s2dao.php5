<?php

class ShelfSearchCDBean {

    const TABLE = "SHELF";
    const shelf_RELNO = 0;
    const cd_RELNO = 1;

    private $shelf;
    private $cd;

    public function setShelf(ShelfBean $shelf){
        $this->shelf = $shelf;
    }

    public function getShelf(){
        return $this->shelf;
    }

    public function setCd(CdBean $cd){
        $this->cd = $cd;
    }

    public function getCd(){
        return $this->cd;
    }
}

?>
