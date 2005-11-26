<?php

/**
 * @author nowel
 */
class S2Dao_PrefixSqlNode extends S2Dao_AbstractNode {

    private $prefix_ = "";
    private $sql_ = "";
    
    public function __construct($prefix, $sql) {
        $this->prefix_ = $prefix;
        $this->sql_ = $sql;
    }
    
    public function getPrefix() {
        return $this->prefix_;
    }

    public function getSql() {
        return $this->sql_;
    }

    public function accept(S2Dao_CommandContext $ctx) {
        if ($ctx->isEnabled()) {
            $ctx->addSql($this->prefix_);
        }
        $ctx->addSql($this->sql_);
    }
}
?>
