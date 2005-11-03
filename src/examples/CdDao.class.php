<?php

interface CdDao {

    const BEAN = "CdBean";
    const insert_NO_PERSISTENT_PROPS = "content";
    const update_NO_PERSISTENT_PROPS = "id, content";
    const List_getCD1_ARGS = "id, title";
    const List_getCD2_ARGS = "id, title, content";
    
    public function update($cd);
    public function insert($cd);
    public function delete($cd);
    
    public function Array_getAllCd();
    public function List_getCD1($id, $title = null);
    public function List_getCD2($id, $title, $content);
}
?>
