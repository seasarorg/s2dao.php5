<?php

/**
 * @author nowel
 */
class SqlNode extends AbstractNode {

    private $sql_ = "";
    
    public function __construct($sql) {
        $this->sql_ = $sql;
    }

    public function getSql() {
        return $this->sql_;
    }

    public function accept(CommandContext $ctx) {
        $ctx->addSql($this->sql_);
    }
}
?>
