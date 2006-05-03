<?php

/**
 * @author nowel
 */
class S2Dao_InsertAutoDynamicCommand implements S2Dao_SqlCommand {

    private $dataSource;
    private $statementFactory;
    private $beanMetaData;
    private $propertyNames = array();
    private $notSingleRowUpdatedExceptionClass;

    public function __construct() {
    }

    public function execute($args){
        $bean = $args[0];
        $bmd = $this->getBeanMetaData();
        $propertyTypes = $this->createInsertPropertyTypes($bmd,
                                                          $bean,
                                                          $this->getPropertyNames());
        $sql = $this->createInsertSql($bmd, $propertyTypes);
        $handler = new S2Dao_InsertAutoHandler($this->getDataSource(),
                                               $this->getStatementFactory(),
                                               $bmd,
                                               $propertyTypes);
        $handler->setSql($sql);
        $rows = $handler->execute($args);
        if ($rows != 1) {
            throw new S2Dao_NotSingleRowUpdatedRuntimeException($args[0], $rows);
        }
        return (int)$rows;
    }

    protected function createInsertSql(S2Dao_BeanMetaData $bmd,
                                       array $propertyTypes) {
        $buf = '';
        $buf .= 'INSERT INTO ';
        $buf .= $bmd->getTableName();
        $buf .= ' (';
        $c = count($propertyTypes);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $propertyTypes[$i];
            $columnName = $pt->getColumnName();
            if (0 < $i) {
                $buf .= ', ';
            }
            $buf .= $columnName;
        }
        $buf .= ') VALUES (';
        for ($i = 0; $i < $c; ++$i) {
            if (0 < $i) {
                $buf .= ', ';
            }
            $buf .= '?';
        }
        $buf .= ')';
        return $buf;
    }

    protected function createInsertPropertyTypes(S2Dao_BeanMetaData $bmd,
                                                 $bean,
                                                 array $propertyNames) {
        $types = new S2Dao_ArrayList();
        $timestampPropertyName = $bmd->getTimestampPropertyName();
        $versionNoPropertyName = $bmd->getVersionNoPropertyName();

        $notNullColumns = 0;
        $c = count($propertyNames);
        for ($i = 0; $i < $c; ++$i) {
            $pt = $bmd->getPropertyType($propertyNames[$i]);
            if ($pt->isPrimaryKey()
                    && !$bmd->getIdentifierGenerator()->isSelfGenerate()) {
                continue;
            }
            if ($pt->getPropertyDesc()->getValue($bean) == null) {
                $propertyName = $pt->getPropertyName();
                if (!strcasecmp($propertyName, $timestampPropertyName) == 0
                        && !strcasecmp($propertyName, $versionNoPropertyName) == 0) {
                    continue;
                }
            } else {
                $notNullColumns++;
            }
            $types->add($pt);
        }
        if ($notNullColumns == 0) {
            throw new S2Container_SRuntimeException('EDAO0014');
        }
        return $types->toArray();
    }

    protected function getDataSource() {
        return $this->dataSource;
    }

    public function setDataSource(S2Container_DataSource $dataSource) {
        $this->dataSource = $dataSource;
    }

    protected function getNotSingleRowUpdatedExceptionClass() {
        return $this->notSingleRowUpdatedExceptionClass;
    }

    public function setNotSingleRowUpdatedExceptionClass($notSingleRowUpdatedExceptionClass) {
        $this->notSingleRowUpdatedExceptionClass = $notSingleRowUpdatedExceptionClass;
    }

    protected function getStatementFactory() {
        return $this->statementFactory;
    }

    public function setStatementFactory(S2Dao_StatementFactory $statementFactory) {
        $this->statementFactory = $statementFactory;
    }

    protected function getBeanMetaData() {
        return $this->beanMetaData;
    }

    public function setBeanMetaData(S2Dao_BeanMetaData $beanMetaData) {
        $this->beanMetaData = $beanMetaData;
    }

    protected function getPropertyNames() {
        return $this->propertyNames;
    }

    public function setPropertyNames(array $propertyNames) {
        $this->propertyNames = $propertyNames;
    }
}
?>