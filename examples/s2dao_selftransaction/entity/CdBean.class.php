<?php

/**
 * @author nowel
 * @S2Dao_BeanAnnotation(TABLE = CD)
 */
class CdBean {
    
    /**
     * @COLUMN = "ID"
     */
    private $id;
    
    /**
     * @COLUMN = "TITLE"
     */
    private $title;
    
    /**
     * @COLUMN = "CONTENT"
     */
    private $content;
    
    public function getContent() {
        return $this->content;
    }
    
    public function setContent($content) {
        $this->content = $content;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getTitle() {
        return $this->title;
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }
}
?>
