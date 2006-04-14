<?php

/**
 * @author nowel
 * @S2Dao_DaoAnnotation(BEAN = CdBean)
 */
interface CdDao {

    public function update(CdBean $cd);
    public function insert(CdBean $cd);
    public function delete(CdBean $cd);

    /**
     * @return list
     */
    public function getAll();
}
?>
