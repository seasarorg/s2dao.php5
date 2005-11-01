<?php

/**
 * @author nowel
 */
class BeginNode extends ContainerNode {

    public function __construct() {
    }
    
    public function accept(CommandContext $ctx) {
        $childCtx = new CommandContextImpl($ctx);
        parent::accept($childCtx);
        if ($childCtx->isEnabled()) {
            $ctx->addSql($childCtx->getSql(),
                         $childCtx->getBindVariables(),
                         $childCtx->getBindVariableTypes());
        }
    }
}
?>
