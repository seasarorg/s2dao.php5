<?php

class ExceptionBeanImpl implements ExceptionBean {
	public function invoke(Exception $e = null){
        if($e === null){
            throw new Exception("hoge");
        }
	    throw $e;
	}
}

?>