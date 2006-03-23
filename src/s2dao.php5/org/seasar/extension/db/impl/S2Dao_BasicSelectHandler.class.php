<?php

/**
 * @author nowel
 */
class S2Dao_BasicSelectHandler extends S2Dao_BasicHandler implements S2Dao_SelectHandler {

    private static $logger_ = null;
    private $resultSetFactory_ = null;
    private $resultSetHandler_;
    private $fetchSize_ = 100;
    private $maxRows_ = -1;

    public function __construct(S2Container_DataSource $dataSource,
                              $sql,
                              S2Dao_ResultSetHandler $resultSetHandler,
                              S2Dao_StatementFactory $statementFacotry = null,
                              S2Dao_ResultSetFactory $resultSetFacotry = null) {

        self::$logger_ = S2Container_S2Logger::getLogger(get_class($this));
        $this->setDataSource($dataSource);
        $this->setSql($sql);
        $this->setResultSetHandler($resultSetHandler);
        
        if(isset($statementFactory, $resultSetFactory)){
            $this->setStatementFactory($statementFactory);
            $this->setResultSetFactory($resultSetFactory);
        } else {
            $this->setStatementFactory(new S2Dao_BasicStatementFactory);
            $this->setResultSetFactory(new S2Dao_BasicResultSetFactory);
        }
    }

    public function getResultSetFactory() {
        return $this->resultSetFactory_;
    }

    public function setResultSetFactory(S2Dao_ResultSetFactory $resultSetFactory = null) {
        $this->resultSetFactory_ = $resultSetFactory;
    }

    public function getResultSetHandler() {
        return $this->resultSetHandler_;
    }

    public function setResultSetHandler(S2Dao_ResultSetHandler $resultSetHandler = null) {
        $this->resultSetHandler_ = $resultSetHandler;
    }

    public function getFetchSize() {
        return $this->fetchSize_;
    }

    public function setFetchSize($fetchSize) {
        $this->fetchSize_ = $fetchSize;
    }

    public function getMaxRows() {
        return $this->maxRows_;
    }

    public function setMaxRows($maxRows) {
        $this->maxRows_ = $maxRows;
    }

    public function execute($element, $args){
        $stmt = $this->prepareStatement($this->getConnection());
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $this->bindArgs($stmt, $element, $args);
        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger_->debug($this->getCompleteSql($element));
        }
        if ($this->resultSetHandler_ == null) {
            throw new S2Container_EmptyRuntimeException('resultSetHandler');
        }

        try{
            $resultSet = $this->createResultSet($stmt);
            // FIXME
            if($stmt->columnCount() == 1){
                $rs = $stmt->fetch(PDO::FETCH_NUM);
                return (int)$rs[0];
            } else {
                return $this->resultSetHandler_->handle($stmt);
            }
        } catch (S2Container_SQLException $ex) {
            throw new S2Container_SQLRuntimeException($ex);
        }
    }

    protected function setup($con, $args) {
        return $args;
    }

    protected function prepareStatement($connection) {
        return parent::prepareStatement($connection);
    }

    protected function setupDatabaseMetaData(DatabaseMetaData $dbMetaData) {
    }

    protected function createResultSet(PDOStatement $stmt) {
        return $this->resultSetFactory_->createResultSet($stmt);
    }
}
?>
