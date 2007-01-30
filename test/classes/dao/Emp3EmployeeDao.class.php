<?php

interface Emp3EmployeeDao {

    const BEAN = "Emp3Employee";

    const find1_SQL = "SELECT ename FROM EMP3";
    function find1();

    const find2_SQL = "SELECT ename AS e_ame FROM EMP3";
    function find2();

    function insert(Emp3Employee $bean);
}

?>