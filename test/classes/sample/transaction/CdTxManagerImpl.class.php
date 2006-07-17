<?php

define('S2DAO_PHP5_USE_COMMENT', true);

/**
 * @author nowel
 */
class CdTxManagerImpl implements CdTxManager {
    
    private $dao = null;
    
    public function __construct(CdDao $dao){
        $this->dao = $dao;
    }
    
    private function createCdBean($id, $title, $content){
        $bean = new CdBean();
        $bean->setId($id);
        $bean->setTitle($title);
        $bean->setContent($content);
        return $bean;
    }
    
    public function requiredInsert(){
        return $this->dao->insert($this->createCdBean(1, "rat race", "metal"));
    }
    
    public function requiresNewInsert(){
        return $this->dao->insert($this->createCdBean(2, "Staring At The Sun", "rock"));
    }
    
    public function mandatoryInsert(){
        return $this->dao->insert($this->createCdBean(3, "Crazy Little Thing Called Love", "rock"));
    }
    
    public function getAll(){
        return $this->dao->getAll();
    }
}

?>