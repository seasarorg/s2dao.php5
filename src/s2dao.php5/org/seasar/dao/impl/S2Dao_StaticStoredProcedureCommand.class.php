<?php

/**
 * @author nowel
 */
class S2Dao_StaticStoredProcedureCommand implements S2Dao_SqlCommand {
    
    private $handler;
    
    public function __construct(S2Dao_ProcedureHandler $handler) {
        $this->handler = $handler;
    }
    
    public function execute($args) {
        return $this->handler->execute($args);
    }

}
?>