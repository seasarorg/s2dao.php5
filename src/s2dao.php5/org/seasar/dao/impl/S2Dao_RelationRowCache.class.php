<?php

/**
 * @author nowel
 */
class S2Dao_RelationRowCache {

    private $rowMapList_ = null;
    
    public function __construct($size) {
        $this->rowMapList_ = new S2Dao_ArrayList();
        for ($i = 0; $i < $size; ++$i) {
            $this->rowMapList_->add(new S2Dao_HashMap());
        }
    }
    
    public function getRelationRow($relno, S2Dao_RelationKey $key) {
        return $this->getRowMap($relno)->get($key->hashCode());
    }
    
    public function addRelationRow($relno, S2Dao_RelationKey $key, $row) {
        $this->getRowMap($relno)->put($key->hashCode(), $row);
    }

    protected function getRowMap($relno) {
        return $this->rowMapList_->get($relno);
    }
}
?>
