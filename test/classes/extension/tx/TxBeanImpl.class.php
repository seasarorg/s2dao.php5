<?php

class TxBeanImpl implements TxBean {

	private $tm_;

	public function __construct(S2Container_DataSource $tm) {
		$this->tm_ = $tm;
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
