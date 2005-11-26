<?php

/**
 * @author nowel
 */
class S2Dao_SqlNode extends S2Dao_AbstractNode {

    private $sql_ = "";
    
    public function __construct($sql) {
        $this->sql_ = $sql;
    }

    public function getSql() {
        return $this->sql_;
    }

    public function accept(S2Dao_CommandContext $ctx) {
        $ctx->addSql($this->sql_);
    }
}
?>
