<?php

/**
 * @author nowel
 */
class S2Dao_ObjectResultSetHandler implements S2Container_ResultSetHandler {

	public function S2Dao_ObjectResultSetHandler($beanClass = null) {
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
