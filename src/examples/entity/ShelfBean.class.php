<?php

class ShelfBean {

    const TABLE = "SHELF";
    const id_ID = "assigned";
    const id_COLUMN = "ID";
    const cdId_COLUMN = "CD_ID";
    const time_COLUMN = "ADD_TIME";

    private $id;
    private $cdId;
    private $time;

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setCdId($id){
        $this->cdId = $id;
    }

    public function getCdId(){
        return $this->cdId;
    }

    public function setTime($time){
        $this->time = $time;
    }

    public function getTime(){
        return $this->time;
    }

    public function setCd(CdBean $cd){
        $this->cd = $cd;
    }

    public function getCd(){
        return $this->cd;
    }
}

?>
