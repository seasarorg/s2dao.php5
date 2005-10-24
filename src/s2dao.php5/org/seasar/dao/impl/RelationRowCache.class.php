<?php

/**
 * @author Yusuke Hata
 */
class RelationRowCache {

    private $rowMapList_ = null;
    
    public function __construct($size) {
        $this->rowMapList_ = new ArrayList();
        for ($i = 0; $i < $size; ++$i) {
            $this->rowMapList_->add(new HashMap());
        }
    }
    
    public function getRelationRow($relno, RelationKey $key) {
        return $this->getRowMap($relno)->get($key);
    }
    
    public function addRelationRow($relno, RelationKey $key, Object $row) {
        $this->getRowMap($relno)->put($key, $row);
    }

    protected function getRowMap($relno) {
        return $this->rowMapList_->get($relno);
    }
}
?>
