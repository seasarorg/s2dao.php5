<?php

interface ShelfDao {

    const BEAN = "ShelfBean";

    const insert_NO_PERSISTENT_PROPS = "id";
    const delete_PERSISTENT_PROPS = "id, cdid";

    const SearchList_SQL = "SELECT * FROM SHELF WHERE ID = /*id*/1 AND CD_ID = /*cdid*/2";
    const SearchByTimeList_SQL = "SELECT * FROM SHELF WHERE ADD_TIME < /*time*/'2005-12-31 12:59:59'";
    const SearchByTrueList_SQL = "
        SELECT * FROM SHELF WHERE
            /*IF time == true*/
                ADD_TIME > '2004-01-01 10:12:34'
            -- ELSE ADD_TIME < /*time*/
            /*END*/";
    
    public function insert(ShelfBean $shelf);
    public function update(ShelfBean $shelf);
    public function delete(ShelfBean $shelf);

    public function SearchList($id, $cdid);
    public function SearchByTimeList($time = null);
    public function SearchByTrueList($time = true);
}

?>
