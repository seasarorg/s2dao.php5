<?php

interface PkOnlyTableDao {
    const BEAN = "PkOnlyTable";
    public function insert(PkOnlyTable $table);
    public function findAllList();
}

?>