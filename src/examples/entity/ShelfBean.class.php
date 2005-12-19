<?php

class ShelfBean {

    const TABLE = "SHELF";
    const id_ID = "identity";

    private $id;
    private $cdid;
    private $addtime;

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

    public function setAddtime($time){
        $this->addtime = $time;
    }

    public function getAddTime(){
        return $this->addtime;
    }
}

?>
