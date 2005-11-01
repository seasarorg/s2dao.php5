<?php

/**
 * @author nowel
 */
class S2DaoBeanListReader extends S2DaoBeanReader {

	public function __construct($list, DatabaseMetaData $dbMetaData) {
		$dbms = DbmsManager::getDbms($dbMetaData);
		$beanMetaData = new BeanMetaDataImpl(
				get_class($list->get(0)), $dbMetaData, $dbms);
		$this->setupColumns($beanMetaData);
		for ($i = 0; $i < $list->size(); ++$i) {
			$this->setupRow($beanMetaData, $list->get($i));
		}
	}

}
?>
