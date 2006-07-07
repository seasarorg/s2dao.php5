<?php

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
    
    /**
     * @return object
     */
    public function getFoo11();
    
    /**
     * @return yaml
     */
    public function getFoo12();
    
    /**
     * @return json
     */
    public function getFoo13();
    
    /**
     * @return obj
     */
    public function getFoo14();
}

?>