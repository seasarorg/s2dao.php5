<?php

/**
 * @author nowel
 * @Dao(bean = CdBean)
 */
interface CdDao {

    /**
     * @return yaml
     */    
    public function getAll();
    
    /**
     * @Sql("SELECT CD.ID, CD.TITLE FROM CD")
     * @return json
     */
    public function getCds();

    /**
     * @return array
     */ 
    public function getSelectCdById($id);

    /**
     * @return yaml
     */
    public function getCD1($id, $title = null);
    
    /**
     * @return json
     */
    public function getCD2($id, $title, $content);
    
    /**
     * @return yaml
     */
    public function getCD3($id = null);

    /**
     * @Sql("SELECT COUNT(*) FROM CD", dbms = mysql)
     */
    public function getCdCount();
}
?>
