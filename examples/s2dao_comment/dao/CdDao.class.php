<?php

/**
 * @author nowel
 * @S2Dao_DaoAnnotation(BEAN = CdBean)
 */
interface CdDao {

    /**
     * @S2Dao_DaoAnnotation(NO_PERSISTENT_PROPS = "id, content")
     */    
    public function update(CdBean $cd);
    
    /**
     * @S2Dao_DaoAnnotation(NO_PERSISTENT_PROPS = "content")
     */
    public function insert(CdBean $cd);
    public function delete(CdBean $cd);

    /**
     * @return array
     */    
    public function getAll();
    
    /**
     * @S2Dao_DaoAnnotation(SQL = "SELECT CD.ID, CD.TITLE FROM CD WHERE ID > 1")
     * @return array
     */
    public function getCds();

    /**
     * @return array
     */ 
    public function getSelectCdById($id);

    /**
     * @return list
     */
    public function getCD1($id, $title = null);
    
    /**
     * @return list
     */
    public function getCD2($id, $title, $content);
    
    /**
     * @return list
     */
    public function getCD3($id = null);

    /**
     * @S2Dao_DaoAnnotation(SQL = "SELECT COUNT(*) FROM CD")
     * @return object
     */
    public function getCdCount();
}
?>
