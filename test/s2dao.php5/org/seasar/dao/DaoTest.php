<?php

interface HogeDao {
    const BEAN = "HogeBean";
}

/**
 * @Dao(bean = FooBean)
 */
interface FooDao {
    
    public function getFoo($id, $num, $desc);
    
    /**
     * @Arguments(id, num, desc)
     */
    public function getFoo2($id, $num, $desc);
    
    /**
     * @NoPersistentProperty(sal, comm)
     */
    public function getFoo3();

    /**
     * @PersistentProperty(sal, comm)
     */
    public function getFoo4();
    
    /**
     * @Query("WHERE id = ? and sal = ?")
     */
    public function getFoo5();

    /**
     * @Sql(SELECT * FROM EMP2)
     */
    public function getFoo6();

    /**
     * @return map
     */
    public function getFoo7();

    /**
     * @return list
     */
    public function getFoo8();
    
    /**
     * @return array
     */
    public function getFoo9();
    
    /**
     * @Procedure(SALES2)
     */
    public function getFoo10();
}

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
}

?>