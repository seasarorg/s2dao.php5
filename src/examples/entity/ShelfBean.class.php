<?php

class ShelfBean {

    const TABLE = "SHELF";
    const id_ID = "assigned";
    const id_COLUMN = "ID";
    const cdid_COLUMN = "CD_ID";
    const time_COLUMN = "ADD_TIME";

    private $id;
    private $cdid;
    private $time;

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setCdId($id){
        $this->cdid = $id;
    }

    public function getCdId(){
        return $this->cdid;
    }

    public function setTime($time){
        $this->time = $time;
    }

    public function getTime(){
        return $this->time;
    }
}

?>
