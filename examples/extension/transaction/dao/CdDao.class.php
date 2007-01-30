<?php

/**
 * @author nowel
 * @Dao(bean = Cd)
 */
interface CdDao {

    public function update(Cd $cd);
    
    public function insert(Cd $cd);
    
    public function delete(Cd $cd);

    /**
     * @return list
     */
    public function getAll();
}
?>
