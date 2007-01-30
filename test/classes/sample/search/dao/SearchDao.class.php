<?php

interface SearchDao {
    
    const BEAN = "Search";
    
    public function get1List();
    
    const get2List_QUERY = "DEPTNO = 30";
    public function get2List();
    
    const get3List_QUERY = "order by DEPTNO DESC";
    public function get3List();
    
    const get4List_QUERY = "DEPTNO = 30 order by DEPTNO";
    public function get4List();
    
    const get5List_QUERY = "where DEPTNO = 30";
    public function get5List();
    
    const get6List_QUERY = "DEPTNO between 10 and 20"; 
    public function get6List();
    
    const get7List_QUERY = "WHERE DEPTNO between 10 and 20";
    public function get7List();
}

?>