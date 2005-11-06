<?php

/**
 * @author nowel
 */
class ObjectResultSetHandler implements ResultSetHandler {

	public function ObjectResultSetHandler($beanClass = null) {
        //parent::__construct($beanClass);
	}

	public function handle($rs) {
		if ($rs->next()) {
			$rsmd = $rs->getMetaData();
			//$valueType = ValueTypes->getValueType(rsmd.getColumnType(1));
            //$valueType = gettype($rsmd);
			//return $valueType->getValue($rs, 1);
            return $rs;
		} else {
			return null;
		}
	}
}
