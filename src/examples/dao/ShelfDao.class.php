<?php

interface ShelfDao {

    const BEAN = "ShelfBean";
    
    public function insert(ShelfBean $shelf);
    public function update(ShelfBean $shelf);
    public function delete(ShelfBean $shelf);
}

?>
