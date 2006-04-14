<?php

/**
 * @author nowel
 */
class CdTxManagerImpl implements CdTxManager {
    
    private $dao = null;
    
    public function __construct(CdDao $dao){
        $this->dao = $dao;
    }
    
    public function requiredInsert(){
        $this->dao->insert(new CdBean(3, "amanda", "newage"));
        $this->dao->insert(new CdBean(4, "rat race", "metal"));
    }
    
    public function requiresNewInsert(){
        $this->dao->insert(new CdBean(4, "Crazy Little Thing Called Love", "rock"));
        $this->dao->insert(new CdBean(3, "Staring At The Sun", "rock"));
    }
    
    public function mandatoryInsert(){
        $this->dao->insert(new CdBean(5, "Prophecy", "metal"));
    }
    
    public function getAll(){
        return $this->dao->getAll();
    }
    
    public function delete(){
        $this->dao->delete(new CdBean(3));
        $this->dao->delete(new CdBean(4));
        $this->dao->delete(new CdBean(5));
    }
}

?>