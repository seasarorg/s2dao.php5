<?php

interface DefaultTableDao {

    const BEAN = "DefaultTable";
    
    const getDefaultTable_ARGS = "id";
    public function getDefaultTable($id);

    const getDefaultTablesList_QUERY = "ORDER BY ID";
    public function getDefaultTablesList();

    public function insert(DefaultTable $largeBinary);

    public function insertBySql(DefaultTable $largeBinary);

    public function update(DefaultTable $largeBinary);

}

?>