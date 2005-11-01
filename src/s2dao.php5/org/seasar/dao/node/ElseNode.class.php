<?php

/**
 * @author nowel
 */
class ElseNode extends ContainerNode {

    public function __construct() {
    }
    
    public function accept(CommandContext $ctx) {
        parent::accept($ctx);
        $ctx->setEnabled(true);
    }
}
?>
