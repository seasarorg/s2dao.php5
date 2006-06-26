<?php

class TxBeanImpl implements TxBean {

	private $tm_;

    public function __construct(S2Container_DataSource $datasource) {
        $this->tm_ = $datasource;
    }

	public function hasTransaction() {
        try {
        		var_dump($this->tm_->beginTransaction());
        } catch(PDOException $e){
            var_dump($e);die;
        }
		return false;
	}

}
