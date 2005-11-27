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
                                S2Container_ResultSetHandler $resultSetHandler,
                                $statementFacotry = null,
                                $resultSetFacotry = null) {

        self::$logger_ = S2Container_S2Logger::getLogger(__CLASS__);
        $this->setDataSource($dataSource);
        $this->setSql($sql);
        $this->setResultSetHandler($resultSetHandler);
        
        /*
        if( isset($statementFactory, $resultSetFactory) ){
            $this->setStatementFactory($statementFactory);
            $this->setResultSetFactory($resultSetFactory);
        } else {
            $this->setStatementFactory( new S2Dao_BasicStatementFactory );
            $this->setResultSetFactory( new S2Dao_BasicResultSetFactory );
        }
        */
    }

    public function getResultSetFactory() {
        return $this->resultSetFactory_;
    }

    public function setResultSetFactory(S2Dao_ResultSetFactory $resultSetFactory) {
        $this->resultSetFactory_ = $resultSetFactory;
    }

    public function getResultSetHandler() {
        return $this->resultSetHandler_;
    }

    public function setResultSetHandler(S2Container_ResultSetHandler $resultSetHandler) {
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

    public function execute( $element, $args = null, $types = null ){
        /*
        if( is_object($element) ){
            $ps = $this->prepareStatement($element);
            $this->bindArgs($ps, $args, $types);
            return $this->execute($ps);
        } else if( !isset($args, $type) ){
            return $this->execute($element, $this->getArgTypes($element));
        } else if( is_array($element) && is_array($args) ){
            if (self::$logger_->isDebugEnabled()) {
                self::$logger_->debug($this->getCompleteSql($element));
            }
            $con = $this->getConnection();
            try {
                return $this->execute($con, $element, $args);
            } catch (S2Container_SQLException $ex) {
                throw new S2Container_SQLRuntimeException($ex);
            }
            $con->close();
        } else {
            // $element is PrerareStatement...
            if ($this->resultSetHandler_ == null) {
                throw new S2Container_EmptyRuntimeException("resultSetHandler");
            }
            $resultSet = $this->createResultSet($element);
            return $this->resultSetHandler_->handle($resultSet);
            ResultSetUtil::close($resultSet);
        }
        */

        $connection = $this->getConnection();
        $ps = $this->prepareStatement($connection);
        $ps->setFetchMode(PDO::FETCH_ASSOC);
        $this->bindArgs($ps, $element, $args);
        $ps->execute();

        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger_->debug($this->getCompleteSql($element));
        }
        
        if ($this->resultSetHandler_ == null) {
            throw new S2Container_EmptyRuntimeException("resultSetHandler");
        }
        //$resultSet = $this->createResultSet($element);
        //return $this->resultSetHandler_->handle($resultSet);
        return $this->resultSetHandler_->handle($ps);
    }

    protected function setup($con, $args) {
        return $args;
    }

    protected function prepareStatement($connection) {
        $ps = parent::prepareStatement($connection);
        /*
        if ($this->fetchSize_ > -1) {
            //StatementUtil::setFetchSize($ps, $this->fetchSize_);
        }
        if ($this->maxRows_ > -1) {
            //StatementUtil::setMaxRows($ps, $this->maxRows_);
        }
        */
        return $ps;
    }

    protected function setupDatabaseMetaData(DatabaseMetaData $dbMetaData) {
    }

    protected function createResultSet($ps) {
        return $this->resultSetFactory_->createResultSet($ps);
    }
}
?>
