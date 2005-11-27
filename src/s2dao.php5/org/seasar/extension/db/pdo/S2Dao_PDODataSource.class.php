<?php

/**
 * @author nowel
 */
class S2Dao_PDODataSource extends S2Container_AbstractDataSource {

    private $log_;
    protected $type = "";
    protected $dsn = "";
    protected $user = "";
    protected $pass = "";
    protected $option = "";
        
    public function __construct(){
        $this->log_ = S2Container_S2Logger::getLogger(__CLASS__);
    }
    
    public function setDsn($dsn){
        $this->dsn = $dsn; 
    }

    public function setPassword($pass){
        $this->pass = $pass;
    }

    public function setOption($option){
        $this->option = $option;
    }

    public function getConnection(){
    	try {
            if( isset($this->user, $this->pass) ){
                $db = new PDO($this->dsn, $this->user, $this->pass);
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

}
?>
