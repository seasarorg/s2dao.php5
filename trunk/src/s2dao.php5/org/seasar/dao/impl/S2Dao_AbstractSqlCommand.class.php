<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractSqlCommand implements S2Dao_SqlCommand {

    private $dataSource_;
    private $statementFactory_;
    private $sql_;
    private $notSingleRowUpdatedExceptionClass_;

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null) {
        
        $this->dataSource_ = $dataSource;
        $this->statementFactory_ = $statementFactory;
    }

    public function getDataSource() {
        return $this->dataSource_;
    }
    
    public function getStatementFactory() {
        return $this->statementFactory_;
    }

    public function getSql() {
        return $this->sql_;
    }

    public function setSql($sql) {
        $this->sql_ = $sql;
    }
    
    public function getNotSingleRowUpdatedExceptionClass() {
        return $this->notSingleRowUpdatedExceptionClass_;
    }
    
    public function setNotSingleRowUpdatedExceptionClass($notSingleRowUpdatedExceptionClass) {
        $this->notSingleRowUpdatedExceptionClass_ = $notSingleRowUpdatedExceptionClass;
    }

}
?>
