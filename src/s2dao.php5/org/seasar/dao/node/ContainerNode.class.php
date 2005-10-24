<?php

/**
 * @author nowel
 */
class ContainerNode extends AbstractNode {

    public function __construct() {
    }

    public function accept(CommandContext $ctx) {
        for ($i = 0; $i < $this->getChildSize(); ++$i) {
            $this->getChild($i)->accept($ctx);
        }
    }
}
?>
