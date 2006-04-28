<?php

/**
 * @author nowel
 * @Bean(table = CD)
 */
class CdBean {

    /**
     * @Column("ID")
     */
    private $id;
    
    /**
     * @Column("TITLE")
     */
    private $title;
    
    /**
     * @Column("CONTENT")
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
