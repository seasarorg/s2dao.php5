<?php

/**
 * @author nowel
 */
class S2Dao_PDODataSource extends S2Dao_AbstractDataSource {

    private $log_;
    protected $type ="";
    protected $dsn ="";
        
    public function __construct(){
        $this->log_ = S2Container_S2Logger::getLogger(__CLASS__);
    }
    
    public function setType($type){
        $this->type = $type;    
    }

    public function setDsn($dsn){
        $this->dsn = $dsn;    
    }

    public function getConnection(){
        if($this->dsn == ""){
            $this->dsn = $this->constructDsn();
        }

    	try {
            $db = new PDO($this->dsn);
    	} catch(PDOException $e){
    		$this->log_->error($e->getMessage(), __METHOD__);
    		$this->log_->error($e->getCode(), __METHOD__);
    		throw new Exception();
    	}
        return $db;
    }

    public function disconnect($connection){
    	unset($connection);
    	$connection = null;
    }

    public function __toString(){
        $str  = 'type = ' . $this->type . ', ';
        $str .= 'user = ' . $this->user . ', ';
        $str .= 'password = ' . $this->password . ', ';
        $str .= 'host = ' . $this->host . ', ';
        $str .= 'port = ' . $this->port . ', ';
        $str .= 'database = ' . $this->database . ', ';
        $str .= 'dsn = ' . $this->dsn;
        return $str;
    }
	
    protected function constructDsnArray(){
        $dsn = array();
        if($this->type != ""){
            $dsn['phptype'] = $this->type;
        }

        if($this->user != ""){
            $dsn['username'] = $this->user;
        }
        
        if($this->password != ""){
            $dsn['password'] = $this->password;
        }

        if($this->host != ""){
            $hp = $this->host;
            if($this->port != ""){
                $hp .= ":" . $this->port;
            }
            
            $dsn['hostspec'] = $hp;
        }

        if($this->database != ""){
            $dsn['database'] = $this->database;
        }
        
        return $dsn;
    }

    protected function constructDsn(){
        $dsn = "";
        if($this->type != ""){
            $dsn .= $this->type . "://";
        }

        if($this->user != ""){
            $dsn .= $this->user;
        }
        
        if($this->password != ""){
            $dsn .= ":" . $this->password;
        }

        if($this->host != ""){
            $dsn .= "@" . $this->host;
            if($this->port != ""){
                $dsn .= ":" . $this->port;
            }
        }

        if($this->database != ""){
            $dsn .= "/" . $this->database;
        }
        
        return $dsn;
    }
}
?>
