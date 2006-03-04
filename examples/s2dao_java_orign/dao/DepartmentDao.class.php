<?php

interface DepartmentDao {

    const BEAN = "Department";

    public function insert(Department $department);
    public function update(Department $department);
    public function delete(Department $department);
}

?>
