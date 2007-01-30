<?php

interface BarDao {
    
    const BEAN = "FooBean";
    
    public function getFoo($id, $num, $desc);
    
    const getFoo2_ARGS = "id, num, desc";
    public function getFoo2($id, $num, $desc);
    
    const getFoo3_NO_PERSISTENT_PROPS = "sal, comm";
    public function getFoo3();

    const getFoo4_PERSISTENT_PROPS = "sal, comm";
    public function getFoo4();
    
    const getFoo5_QUERY = "WHERE id = ? and sal = ?";
    public function getFoo5();

    const getFoo6_SQL = "SELECT * FROM EMP2";
    public function getFoo6();

    public function getFoo7Map();
    public function getFoo8List();
    public function getFoo9Array();
    
    const getFoo10_PROCEDURE = "SALES2";
    public function getFoo10();
    
    public function getFoo11Object();
    public function getFoo11Obj();
    public function getFoo12Yaml();
    public function getFoo13Json();
    public function getFoo14Map();
}

?>