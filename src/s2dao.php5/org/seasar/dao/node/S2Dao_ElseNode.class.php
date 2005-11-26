<?php

/**
 * @author nowel
 */
class S2Dao_ElseNode extends S2Dao_ContainerNode {

    public function __construct() {
    }
    
    public function accept(S2Dao_CommandContext $ctx) {
        parent::accept($ctx);
        $ctx->setEnabled(true);
    }
}
?>
