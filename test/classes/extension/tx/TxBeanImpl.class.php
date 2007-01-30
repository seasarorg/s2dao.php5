<?php

class TxBeanImpl implements TxBean {

	private $tm_;

    public function __construct(S2Container_DataSource $datasource) {
        $this->tm_ = $datasource->getConnection();
    }

	public function hasTransaction() {
        try {
        		var_dump($this->tm_->beginTransaction());
        } catch(PDOException $e){
            if(strcmp($e->getMessage(), "There is already an active transaction") == 0){
                return true;
            }
        }
		return false;
	}

}
