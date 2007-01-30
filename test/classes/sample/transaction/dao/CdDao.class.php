<?php

/**
 * @author nowel
 */
interface CdDao {
    
    const BEAN = "CdBean";

    public function update(CdBean $cd);
    public function insert(CdBean $cd);
    public function delete(CdBean $cd);

    public function getAllList();
}
?>
