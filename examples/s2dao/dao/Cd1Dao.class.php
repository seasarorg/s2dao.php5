<?php

interface Cd1Dao {

    const BEAN = "CdBean";

    const insert_NO_PERSISTENT_PROPS = "content";
    const update_NO_PERSISTENT_PROPS = "id, content";

    const getSelectCdByIdArray_ARGS = "id";
    const getCD1List_ARGS = "id, title";
    const getCD2List_ARGS = "id, title, content";
    const getCD3List_ARGS = "id";

    const getCdsArray_SQL = "SELECT CD.ID, CD.TITLE FROM CD WHERE ID > 1";
    const getSelectCdByIdArray_QUERY = "ID = /*id*/1";

    const getCdCount_SQL = "SELECT COUNT(*) FROM CD";
    
    // auto update methods
    public function update(CdBean $cd);
    public function modify(CdBean $cd);
    public function store(CdBean $cd);
    // auto insert methods
    public function insert(CdBean $cd);
    public function create(CdBean $cd);
    public function add(CdBean $cd);
    // auto delete methods
    public function delete(CdBean $cd);
    public function remove(CdBean $cd);
    
    public function getAllCdList();
    public function getCdsArray();
    public function getSelectCdByIdArray($id);
    public function getCD1List($id, $title = null);
    public function getCD2List($id, $title, $content);
    public function getCD3List($id = null);

    public function getCdCount();
}
?>
