<?php

interface ShelfDao {

    const BEAN = "ShelfBean";
    const insert_NO_PERSISTENT_PROPS = "id";
    const delete_PERSISTENT_PROPS = "id, cdid";
    
    public function insert(ShelfBean $shelf);
    public function update(ShelfBean $shelf);
    public function delete(ShelfBean $shelf);
}

?>
