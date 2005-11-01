<?php

/**
 * @author nowel
 */
class PDOSqlHandler implements SqlHandler {

    private $log_;

    public function __construct(){
        $this->log_ = S2Logger::getLogger(__CLASS__);
    }

    public function execute($sql,
                            DataSource $dataSource,
                            ResultSetHandler $resultSetHandler) {
        
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
