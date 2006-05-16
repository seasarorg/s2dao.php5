<?php

/**
 * @author nowel
 */
class S2Dao_BasicUpdateHandler
    extends S2Dao_BasicHandler 
    implements S2Dao_UpdateHandler {

    private static $logger_ = null;
    
    public function __construct(S2Container_DataSource $dataSource,
                                $sql,
                                S2Dao_StatementFactory $statementFactory = null) {
        self::$logger_ = S2Container_S2Logger::getLogger(get_class($this));
        parent::__construct($dataSource, $sql, $statementFactory);
    }
    
    public function execute($args, $argsTypes){
        $stmt = $this->prepareStatement($this->getConnection());
        $this->bindArgs($stmt, $args, $argsTypes);
        if(S2CONTAINER_PHP5_LOG_LEVEL == 1){
            self::$logger_->debug($this->getCompleteSql($args));
        }

        try {
            $stmt->execute();
            return $stmt->rowCount();
        } catch (S2Container_SQLException $e) {
            throw new S2Container_SQLRuntimeException($e);
        }

    }
}

?>