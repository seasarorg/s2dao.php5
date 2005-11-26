<?php

/**
 * @author nowel
 */
class S2Dao_BeginNode extends S2Dao_ContainerNode {

    public function __construct() {
    }
    
    public function accept(S2Dao_CommandContext $ctx) {
        $childCtx = new S2Dao_CommandContextImpl($ctx);
        parent::accept($childCtx);
        if ($childCtx->isEnabled()) {
            $ctx->addSql($childCtx->getSql(),
                         $childCtx->getBindVariables(),
                         $childCtx->getBindVariableTypes());
        }
    }
}
?>
