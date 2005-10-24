<?php

/**
 * @author nowel
 */
class BasicSelectHandler extends BasicHandler implements SelectHandler {

    private static $logger_ = null;

    private $resultSetFactory_ = null;

    private $resultSetHandler_;

    private $fetchSize_ = 100;

    private $maxRows_ = -1;

    public function __construct( DataSource $dataSource,
                                 $sql,
                                 ResultSetHandler $resultSetHandler,
                                 $statementFacotry = null,
                                 $resultSetFacotry = null) {

        //self::$logger_ = Logger.getLogger(__CLASS__);
        $this->setDataSource($dataSource);
        $this->setSql($sql);
        $this->setResultSetHandler($resultSetHandler);
        
        if( isset($statementFactory, $resultSetFactory) ){
            $this->setStatementFactory($statementFactory);
            $this->setResultSetFactory($resultSetFactory);
        } else {
            $this->setStatementFactory( new BasicStatementFactory );
            $this->setResultSetFactory( new BasicResultSetFactory );
        }
    }

    public function getResultSetFactory() {
        return $this->resultSetFactory_;
    }

    public function setResultSetFactory(ResultSetFactory $resultSetFactory) {
        $this->resultSetFactory_ = $resultSetFactory;
    }

    public function getResultSetHandler() {
        return $this->resultSetHandler_;
    }

    public function setResultSetHandler(ResultSetHandler $resultSetHandler) {
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
            } catch (SQLException $ex) {
                throw new SQLRuntimeException($ex);
            }
            $con->close();
        } else {
            // $element is PrerareStatement...
            if ($this->resultSetHandler_ == null) {
                throw new EmptyRuntimeException("resultSetHandler");
            }
            $resultSet = $this->createResultSet($element);
            return $this->resultSetHandler_->handle($resultSet);
            ResultSetUtil::close($resultSet);
        }
        */

        $connection = $this->getConnection();
        $connection->setFetchMode(DB_FETCHMODE_ASSOC);
        $ps = $this->prepareStatement($connection);
        $this->bindArgs($ps, $element, $args);
        
        $result = $connection->execute($ps, $element);
        var_dump($this->getCompleteSql($element));
        
        if ($this->resultSetHandler_ == null) {
            throw new EmptyRuntimeException("resultSetHandler");
        }
        //$resultSet = $this->createResultSet($element);
        //return $this->resultSetHandler_->handle($resultSet);
        return $this->resultSetHandler_->handle($result);
    }

    protected function setup($con, $args) {
        return $args;
    }

    protected function prepareStatement($connection) {
        $ps = parent::prepareStatement($connection);
        if ($this->fetchSize_ > -1) {
            //StatementUtil::setFetchSize($ps, $this->fetchSize_);
        }
        if ($this->maxRows_ > -1) {
            //StatementUtil::setMaxRows($ps, $this->maxRows_);
        }
        return $ps;
    }

    protected function setupDatabaseMetaData(DatabaseMetaData $dbMetaData) {
    }

    protected function createResultSet($ps) {
        return $this->resultSetFactory_->createResultSet($ps);
    }
}
?>
