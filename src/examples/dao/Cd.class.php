<?php

interface Cd {

    const BEAN = "CdBean";

    const insert_NO_PERSISTENT_PROPS = "content";
    const update_NO_PERSISTENT_PROPS = "id, content";

    const Array_getSelectCdById_ARGS = "id";
    const List_getCD1_ARGS = "id, title";
    const List_getCD2_ARGS = "id, title, content";
    const List_getCD3_ARGS = "id";

    const getCds_SQL = "SELECT CD.ID, CD.TITLE FROM CD WHERE ID > 1";

    const Array_getSelectCdById_QUERY = "ID = /*id*/1";
    
    public function update(CdBean $cd);
    public function insert(CdBean $cd);
    public function delete(CdBean $cd);
    
    public function Array_getAllCd();
    public function Array_getSelectCdById($id);
    public function List_getCD1($id, $title = null);
    public function List_getCD2($id, $title, $content);
    public function List_getCD3($id = null);

    public function getCds();
}
?>
