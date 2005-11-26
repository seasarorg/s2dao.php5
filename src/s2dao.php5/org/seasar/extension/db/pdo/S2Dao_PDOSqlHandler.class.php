<?php

/**
 * @author nowel
 */
class S2Dao_PDOSqlHandler implements S2Dao_SqlHandler {

    private $log_;

    public function __construct(){
        $this->log_ = S2Container_S2Logger::getLogger(__CLASS__);
    }

    public function execute($sql,
                            S2Container_DataSource $dataSource,
                            S2Container_ResultSetHandler $resultSetHandler) {
        
        $db = $dataSource->getConnection();
        
        try {
            $stmt = $db->query($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            $this->log_->error($e->getMessage(), __METHOD__);
            $this->log_->error($e->getCode(), __METHOD__);
            exit();
        }
        
        $count = $stmt->rowCount();
        if($count > 0){
            return $count;
        }
        
        $ret = array();
        while($row = $stmt->fetchRow()){
            array_push($ret, $resultSetHandler->handle($row));
        }
        unset($stmt);
        unset($db);
        return $ret;
     }
}
?>
