<?php

/**
 * @author nowel
 */
abstract class S2Dao_AbstractDynamicCommand extends S2Dao_AbstractSqlCommand {

    private $rootNode_;
    private $argNames_ = array();
    private $argTypes_ = array();

    public function __construct(S2Container_DataSource $dataSource,
                                S2Dao_StatementFactory $statementFactory = null){
        parent::__construct($dataSource, $statementFactory);
    }

    public function setSql($sql) {
        parent::setSql($sql);
        $sqlparser = new S2Dao_SqlParserImpl($sql);
        $this->rootNode_ = $sqlparser->parse();
    }

    public function getArgNames() {
        return $this->argNames_;
    }

    public function setArgNames($argNames) {
        $this->argNames_ = $argNames;
    }

    public function getArgTypes() {
        return $this->argTypes_;
    }

    public function setArgTypes($argTypes) {
        $this->argTypes_ = $argTypes;
    }

    protected function apply($args) {
        $ctx = $this->createCommandContext($args);
        $this->rootNode_->accept($ctx);
        return $ctx;
    }

    protected function createCommandContext($args) {
        $ctx = new S2Dao_CommandContextImpl();
        if ($args != null) {
            for ($i = 0; $i < count($args); ++$i) {
                $argType = null;
                //if ($args[$i] != null)
                if ($i < count($this->argTypes_)) {
                    $argType = $this->argTypes_[$i];
                } else if ($args[$i] != null) {
                    $argType = gettype($args[$i]);
                }
                if ($i < count($this->argNames_)) {
                    $ctx->addArg($this->argNames_[$i], $args[$i], $argType);
                } else {
                    $ctx->addArg('$' . ($i + 1), $args[$i], $argType);
                }
            }
        }
        return $ctx;
    }
}
?>
