<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractAutoStaticCommand extends S2Dao_AbstractStaticCommand {

    private $propertyTypes_ = array();

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null,
                                S2Dao_BeanMetaData $beanMetaData,
                                $propertyNames) {

        parent::__construct($dataSource, $statementFactory, $beanMetaData);
        $this->setupPropertyTypes($propertyNames);
        $this->setupSql();
    }

    public function execute($args) {
        $handler = $this->createAutoHandler();
        $handler->setSql($this->getSql());
        $rows = $handler->execute($args);
        if ($rows != 1) {
            throw new S2Dao_NotSingleRowUpdatedRuntimeException(get_class($args[0]), $rows);
        }
        return (int)$rows;
    }

    protected function getPropertyTypes() {
        return $this->propertyTypes_;
    }

    protected function setPropertyTypes($propertyTypes) {
        $this->propertyTypes_ = $propertyTypes;
    }

    protected abstract function createAutoHandler();

    protected abstract function setupPropertyTypes($propertyNames);

    protected function setupInsertPropertyTypes(array $propertyNames) {
        $types = new S2Dao_ArrayList();
        $c = count($propertyNames);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $this->getBeanMetaData()->getPropertyType($propertyNames[$i]);
            if ($pt->isPrimaryKey() &&
                !$this->getBeanMetaData()->getIdentifierGenerator()->isSelfGenerate()) {
                continue;
            }
            $types->add($pt);
        }
        $this->propertyTypes_ = $types->toArray();
    }

    protected function setupUpdatePropertyTypes($propertyNames) {
        $types = new S2Dao_ArrayList();
        $c = count($propertyNames);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $this->getBeanMetaData()->getPropertyType($propertyNames[$i]);
            if ($pt->isPrimaryKey()) {
                continue;
            }
            $types->add($pt);
        }
        $this->propertyTypes_ = $types->toArray();
    }

    protected function setupDeletePropertyTypes($propertyNames){}

    protected abstract function setupSql();

    protected function setupInsertSql() {
        $buf = '';
        $buf .= 'INSERT INTO ';
        $buf .= $this->getBeanMetaData()->getTableName();
        $buf .= ' (';
        $c = count($this->propertyTypes_);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $this->propertyTypes_[$i];
            $buf .= $pt->getColumnName();
            $buf .= ', ';
        }
        $buf = preg_replace('/(, )$/', '', $buf);
        $buf .= ') VALUES (';
        for ($i = 0; $i < $c; ++$i) {
            $buf .= '?, ';
        }
        $buf = preg_replace('/(, )$/', '', $buf);
        $buf .= ')';
        $this->setSql($buf);
    }

    protected function setupUpdateSql() {
        $this->checkPrimaryKey();
        $buf = '';
        $buf .= 'UPDATE ';
        $buf .= $this->getBeanMetaData()->getTableName();
        $buf .= ' SET ';
        $c = count($this->propertyTypes_);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $this->propertyTypes_[$i];
            $buf .= $pt->getColumnName();
            $buf .= ' = ?, ';
        }
        $buf = preg_replace('/(, )$/', '', $buf);
        $this->setupUpdateWhere($buf);
        $this->setSql($buf);
    }

    protected function setupDeleteSql() {
        $this->checkPrimaryKey();
        $buf = '';
        $buf .= 'DELETE FROM ';
        $buf .= $this->getBeanMetaData()->getTableName();
        $this->setupUpdateWhere($buf);
        $this->setSql($buf);
    }

    protected function checkPrimaryKey() {
        $bmd = $this->getBeanMetaData();
        if ($bmd->getPrimaryKeySize() == 0) {
            throw new S2Dao_PrimaryKeyNotFoundRuntimeException($bmd->getBeanClass());
        }
    }

    protected function setupUpdateWhere(&$buf) {
        $bmd = $this->getBeanMetaData();
        $buf .= ' WHERE ';
        for ($i = 0; $i < $bmd->getPrimaryKeySize(); ++$i) {
            $buf .= $bmd->getPrimaryKey($i);
            $buf .= ' = ? AND ';
        }
        $buf = preg_replace('/( AND )$/', '', $buf);
        if ($bmd->hasVersionNoPropertyType()) {
            $pt = $bmd->getVersionNoPropertyType();
            $buf .= ' AND ';
            $buf .= $pt->getColumnName();
            $buf .= ' = ?';
        }
        if ($bmd->hasTimestampPropertyType()) {
            $pt = $bmd->getTimestampPropertyType();
            $buf .= ' AND ';
            $buf .= $pt->getColumnName();
            $buf .= ' = ?';
        }
    }
}
?>
