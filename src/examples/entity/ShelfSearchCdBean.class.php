<?php

class ShelfSearchCDBean {

    const TABLE = "SHELF";

    const shelf_RELNO = 0;
    const id_COLUMN = "ID_0";
    const cdId_COLUMN = "CD_ID_0";
    const time_COLUMN = "ADD_TIME_0";

    const cd_RELNO = 1;
    const cd_RELKEYS = "CD_ID:ID";
    const cd_Content_COLUMN = "CONTENT_1";
    const cd_Id_COLUMN = "ID_1";
    const cd_Title_COLUMN = "TITLE_1";

    const NO_PERSISTENT_PROPS = "cd_Content, cd_Id, cd_Title";

    private $id;
    private $cdId;
    private $time;
    private $cd_Id;
    private $cd_Content;
    private $cd_Title;
    private $shelf;
    private $cd;

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

    public function setCd_Id($id){
        $this->cd_Id = $id;
    }

    public function getCd_Id(){
        return $this->cd_Id;
    }

    public function setCd_Content($content){
        $this->cd_Content = $content;
    }

    public function getCd_Content(){
        return $this->cd_Content;
    }

    public function setCd_Title($title){
        $this->cd_Title = $title;
    }

    public function getCd_Title(){
        return $this->cd_Title;
    }

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
