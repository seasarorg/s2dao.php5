<?php

/**
 * @author nowel
 */
class S2Dao_PDODataSource extends S2Container_AbstractDataSource {

    private $log_;
    protected $dsn = "";
    protected $option = "";
        
    public function __construct(){
        $this->log_ = S2Container_S2Logger::getLogger(__CLASS__);
    }
    
    public function setDsn($dsn){
        $this->dsn = $dsn; 
    }

    public function setOption($option){
        $this->option = $option;
    }

    public function getConnection(){
        try {
            if( !empty($this->option) ){
                $this->dsn .= " " . $this->option;
            }
            if( !empty($this->user) || !empty($this->password) ){
                $db = new PDO($this->dsn, $this->user, $this->password);
            } else {
                $db = new PDO($this->dsn);
            }
        } catch(PDOException $e){
            $this->log_->error($e->getMessage(), __METHOD__);
            $this->log_->error($e->getCode(), __METHOD__);
            throw $e;
        }
        return $db;
    }

    public function disconnect($connection){
        unset($connection);
    }

    public function __toString(){
        $str .= 'user = ' . $this->user . ', ';
        $str .= 'password = ' . $this->password . ', ';
        $str .= 'dsn = ' . $this->dsn . ', ';
        $str .= 'option = ' . $this->option;
        return $str;
    }

}
?>
