<?php

/**
 * @author nowel
 * @Dao(bean = CdBean)
 */
interface CdDao {

    /**
     * @NoPersistentProperty(id, content)
     */    
    public function update(CdBean $cd);
    
    /**
     * @NoPersistentProperty(content)
     */
    public function insert(CdBean $cd);
    public function delete(CdBean $cd);

    /**
     * @return array
     */    
    public function getAll();
    
    /**
     * @Sql("SELECT CD.ID, CD.TITLE FROM CD WHERE ID > 1")
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
     * @Sql("SELECT COUNT(*) FROM CD", dbms = mysql)
     */
    public function getCdCount();
}
?>
