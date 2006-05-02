<?php

/**
 * @author nowel
 */
class S2DaoTestCase extends S2TestCase {

    public function __construct($name = null) {
        if($name != null){
            parent::__construct($name);
        }
    }

    protected function assertBeanEquals($message, S2Dao_DataSet $expected, $bean) {
        $reader = new S2DaoBeanReader($bean,$this->getDatabaseMetaData());
        $this->assertEquals($message, $expected, $reader->read());
    }

    protected function assertBeanListEquals($message, S2Dao_DataSet $expected, $list) {
        $reader = new S2DaoBeanListReader($list, $this->getDatabaseMetaData());
        $this->assertEquals($message, $expected, $reader->read());
    }

    protected function getDbms() {
        $dbMetaData = $this->getDatabaseMetaData();
        return S2Dao_DbmsManager::getDbms($dbMetaData);
    }

}
?>
