<?php

/**
 * @author nowel
 * @Dao(bean = CdBean)
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
